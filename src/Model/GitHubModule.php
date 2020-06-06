<?php

namespace Sunnysideup\ModuleChecks\Model;

use Exception;
use FileSystem;

use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\Exception\GitException;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataObject;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;


class GitHubModule extends DataObject
{
    /**
     * wrapper also relates to one git hub repo only!!!!
     *
     */
    protected $commsWrapper = null;

    /**
     * @var git module
     */
    protected $gitRepo = null;

    /**
     * e.g.
     * @var string
     */
    private static $github_account_base_url = '';

    /**
     * e.g. boss
     * @var string
     */
    private static $github_user_name = '';

    /**
     * @var string
     */
    private static $github_user_email = '';

    /**
     * @var string
     */
    private static $path_to_private_key = '~/.ssh/id_rsa';

    /**
     * where the git module is temporary
     * cloned and fixed up
     * should be an absolute_path
     *
     * @var string
     */
    private static $absolute_temp_folder = '/var/www/temp/';

    private static $table_name = 'GitHubModule';

    private static $db = [
        'ModuleName' => 'Varchar(100)',
        'Description' => 'Varchar(300)',
        'ForksCount' => 'Int',
        'DefaultBranch' => 'Varchar(100)',
        'Private' => 'Boolean',
        'HomePage' => 'Varchar(100)',
    ];

    private static $summary_fields = [
        'ModuleName' => 'Name',
        'Description' => 'Description',
        'ForksCount' => 'Count',
        'DefaultBranch' => 'Branch',
        'Private.Nice' => 'private',
        'HomePage' => 'HomePage',
    ];

    private static $searchable_fields = [
        'ModuleName' => 'PartialMatchFilter',
        'Description' => 'PartialMatchFilter',
        'DefaultBranch' => 'PartialMatchFilter',
        'Private' => 'ExactMatchFilter',
        'HomePage' => 'PartialMatchFilter',
    ];

    private static $indexes = [
        'ModuleName' => true,
    ];

    private static $casting = [
        'Directory' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
    ];

    private $_latest_tag = null;

    public function getDirectory()
    {
        return $this->Directory();
    }

    /**
     * absolute path
     * @return string | null
     */
    public function Directory()
    {
        $tempFolder = $this->Config()->get('absolute_temp_folder');
        if ($this->ModuleName) {
            $folder = $tempFolder . '/' . $this->ModuleName;
            if (file_exists($folder)) {
                if (file_exists($folder)) {
                    return $folder;
                }
            } else {
                mkdir($folder);
                if (file_exists($folder)) {
                    return $folder;
                }
            }
        }
    }

    public function getURL()
    {
        return $this->URL();
    }

    public function LongModuleName()
    {
        return $this->Config()->get('github_user_name') . '/' . $this->ModuleName;
    }

    public function MediumModuleName()
    {
        return $this->ModuleName;
    }

    /**
     * @todo: check that silverstripe- is at the start of string.
     * @return string
     */
    public function ShortModuleName()
    {
        return str_replace('silverstripe-', '', $this->ModuleName);
    }

    public function ShortUCFirstName()
    {
        $array = explode('_', $this->ShortModuleName());

        $name = '';

        foreach ($array as $part) {
            $name .= ucfirst($part);
        }

        return $name;
    }

    public function ModuleNameFirstLetterCapital()
    {
        $shortName = $this->ShortModuleName();

        $firstLetterCapitalName = str_replace('_', ' ', $shortName);
        $firstLetterCapitalName = str_replace('-', ' ', $firstLetterCapitalName);

        return strtolower($firstLetterCapitalName);
    }

    public function setDescription($str)
    {
        $this->Description = trim($str);
    }

    /**
     * check if URL exists and returns it
     * @var string | null
     */
    public function URL(): string
    {
        $username = $this->Config()->get('github_user_name');

        return 'https://github.com/' . $username . '/' . $this->ModuleName;
    }

    /**
     * @param bool (optional) $forceNew - create a new repo and ditch all changes
     * @return Git Repo Object
     */
    public function checkOrSetGitCommsWrapper($forceNew = false): GitWorkingCopy
    {
        //check if one has been created already...
        if (! $this->gitRepo) {
            //basic check
            if ($this->ModuleName === '') {
                user_error('ModuleName element must be set before using git repository commands');
            }

            //create comms
            $this->commsWrapper = new GitWrapper();

            // Stream output of subsequent Git commands in real time to STDOUT and STDERR.
            if (Director::is_cli()) {
                $this->commsWrapper->streamOutput();
            }

            if (! $this->Config()->get('path_to_private_key')) {
                user_error('We recommend you set private key');
            }
            // Optionally specify a private key other than one of the defaults.
            $this->commsWrapper->setPrivateKey($this->Config()->get('path_to_private_key'));

            //if directory exists, return existing repo,
            //otherwise clone it....
            if ($this->IsDirGitRepo($this->Directory())) {
                if ($forceNew) {
                    $this->removeClone();
                    return $this->checkOrSetGitCommsWrapper(false);
                }
                $this->gitRepo = $this->commsWrapper->workingCopy($this->Directory());
            } else {
                GeneralMethods::output_to_screen('cloning ... ' . $this->fullGitURL(), 'created');

                $this->gitRepo = null;
                $cloneAttempts = 0;
                while (! $this->gitRepo) {
                    $cloneAttempts ++;
                    if ($cloneAttempts === 4) {
                        user_error('Failed to clone module ' . $this->LongModuleName() . ' after ' . ($cloneAttempts - 1) . ' attemps.', E_USER_ERROR);
                        //UpdateModules::$unsolvedItems[$this->ModuleName()] = 'Failed to clone modules';
                        UpdateModules::addUnsolvedProblem($this->ModuleName(), 'Failed to clone modules');
                    }
                    try {
                        $this->commsWrapper->setTimeout(240); //Big modules need a longer timeout
                        $this->gitRepo = $this->commsWrapper->cloneRepository(
                            $this->fullGitURL(),
                            $this->Directory()
                        );
                        $this->commsWrapper->setTimeout(60);
                    } catch (Exception $e) {
                        if (strpos($e->getMessage(), 'already exists and is not an empty directory') !== false) {
                            user_error($e->getMessage(), E_USER_ERROR);
                        }

                        GeneralMethods::output_to_screen('<li>Failed to clone repository: ' . $e->getMessage() . '</li>');
                        GeneralMethods::output_to_screen('<li>Waiting 8 seconds to try again ...: </li>');
                        $this->removeClone();
                        sleep(8);
                    }
                }
            }
            $this->gitRepo->config('push.default', 'simple');
            $this->gitRepo->config('user.name', $this->Config()->get('github_user_name'));
            $this->gitRepo->config('user.email', $this->Config()->get('github_user_email'));
            $this->commsWrapper->git('config -l');
        }
        return $this->gitRepo;
    }

    /**
     * @var string
     */
    public function fullGitURL()
    {
        $username = $this->Config()->get('github_user_name');
        // $gitURL = $this->Config()->get('github_account_base_url');
        return 'git@github.com:/' . $username . '/' . $this->ModuleName . '.git';
    }

    /**
     * pulls a git repo
     *
     * @return bool
     */
    public function pull()
    {
        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            try {
                $git->pull();
            } catch (GitException $e) {
                print_r($e);
                throw $e;
                return false;
            }
            return true;

            //GeneralMethods::output_to_screen($git->getOutput());
        }
        return false;
    }

    /**
     * commits a git repo
     *
     * @param string $message
     *
     * @return bool
     */
    public function commit($message = 'PATCH: module clean-up') : bool
    {
        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            try {
                $git->commit($message);
            } catch (Exception $e) {
                $errStr = $e->getMessage();
                if (stripos($errStr, 'nothing to commit') === false) {
                    print_r($e);
                    throw $e;
                }
                GeneralMethods::output_to_screen('No changes to commit');
                return false;
            }
            //GeneralMethods::output_to_screen($git->getOutput());

            return true;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     * @return bool
     */
    public function add() : bool
    {
        GeneralMethods::output_to_screen('Adding new files to ' . $this->ModuleName . ' ...  ', 'created');

        $git = $this->checkOrSetGitcommsWrapper();
        if ($git) {
            try {
                $git->add('.');
            } catch (GitException $e) {
                $errStr = $e->getMessage();
                if (stripos($errStr, 'did not match any files') === false) {
                    print_r($e);
                    throw $e;
                }
                GeneralMethods::output_to_screen('No new files to add to $module. ');
                return false;
            }

            //GeneralMethods::output_to_screen($git->getOutput());

            return true;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     *
     * @return bool
     */
    public function push() : bool
    {
        GeneralMethods::output_to_screen('Pushing files to ' . $this->ModuleName . ' ...  ', 'created');

        $git = $this->checkOrSetGitcommsWrapper();
        if ($git) {
            $pushed = false;
            $pushAttempts = 0;
            while (! $pushed) {
                $pushAttempts ++;
                try {
                    $git->push();
                    $pushed = true;
                } catch (Exception $e) {
                    if ($pushAttempts === 3) {
                        $git->getOutput();
                        print_r($e);
                        throw $e;
                    }
                    GeneralMethods::output_to_screen('<li>Failed to push repository: ' . $e->getMessage() . '</li>');
                    GeneralMethods::output_to_screen('<li>Waiting 8 seconds to try again ...: </li>');
                    sleep(8);
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * removes a cloned repo
     */
    public function removeClone()
    {
        $dir = $this->Directory();
        GeneralMethods::output_to_screen('Removing ' . $dir . ' and all its contents ...  ', 'created');
        $this->gitRepo = null;
        FileSystem::removeFolder($dir); // removes contents but not the actual folder
        //rmdir ($dir);
        return ! file_exists($dir);
    }

    /**
     * retrieves a raw file from Github
     *
     * @return string | bool
     */
    public function getRawFileFromGithub($fileName)
    {
        $gitUserName = $this->Config()->get('github_user_name');
        $branch = 'master';

        $rawURL = 'https://raw.githubusercontent.com/' . $gitUserName . '/' . $this->ModuleName . '/' . $branch . '/' . $fileName;

        set_error_handler([$this, 'catchFopenWarning'], E_WARNING);
        $file = fopen($rawURL, 'r');
        restore_error_handler();

        if (! $file) {
            GeneralMethods::output_to_screen('<li>Could not find ' . $rawURL . '</li>');
            return false;
        }
        $content = '';
        while (! feof($file)) {
            $content .= fgets($file);
        }
        fclose($file);
        return $content;
    }

    public static function get_or_create_github_module(array $moduleDetails) : self
    {
        $moduleName = trim($moduleName[$moduleDetails['name']]);
        $filter = ['ModuleName' => $moduleName];
        $gitHubModule = GitHubModule::get()->filter($filter)->first();
        if (! $gitHubModule) {
            $gitHubModule = GitHubModule::create($filter);
            $gitHubModule->write();
        }

        return $gitHubModule;
    }

    public function getLatestCommitTime()
    {
        // equivalent to git log -1 --format=%cd .

        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            $options = [
                'format' => '%cd',
                '1' => true,
            ];

            try {
                $result = $git->log($options);
            } catch (Exception $e) {
                $errStr = $e->getMessage();
                if (stripos($errStr, 'does not have any commits') === false) {
                    print_r($e);
                    throw $e;
                }
                return false;
            }

            if ($result) {
                return strtotime($result);
            }
            return false;
        }
        return false;
    }

    public function getLatestTag()
    {
        if ($this->_latest_tag === null) {
            $git = $this->checkOrSetGitCommsWrapper();
            if ($git) {
                $options = [
                    'tags' => true,
                    'simplify-by-decoration' => true,
                    'pretty' => 'format:%ai %d',
                ];

                $cwd = getcwd();
                chdir($this->Directory);

                try {
                    $result = $git->log($options);
                } catch (Exception $e) {
                    $errStr = $e->getMessage();
                    if (stripos($errStr, 'does not have any commits') === false) {
                        print_r($e);
                        throw $e;
                    }
                    GeneralMethods::output_to_screen('Unable to get tag because there are no commits to the repository');
                    return false;
                }

                chdir($cwd);

                $resultLines = explode("\n", $result->getOutput());

                // 2016-10-14 12:29:08 +1300 (HEAD -> master, tag: 2.3.0, tag: 2.2.0, tag: 2.1.0, origin/master, origin/HEAD)\
                // or
                // 2016-08-29 17:18:22 +1200 (tag: 2.0.0)
                //print_r($resultLines);

                if (count($resultLines) === 0) {
                    return false;
                }

                $latestTimeStamp = 0;
                $latestTag = false;
                foreach ($resultLines as $line) {
                    $isTagInLine = (strpos($line, 'tag') !== false);
                    if ($isTagInLine) {
                        $tagStr = trim(substr($line, 25));
                        $dateStr = trim(substr($line, 0, 26));

                        //extract tag numbers from $tagStr

                        $matches = [];
                        // print_r ("original!!! " .  $tagStr);
                        $result = preg_match_all('/tag: \d{1,3}.\d{1,3}.\d{1,3}/', $tagStr, $matches);
                        if ($result === false) {
                            continue;
                        } elseif ($result > 1) {
                            $tagStr = $matches[0][0];
                        }
                        //print_r ($matches);

                        $tagStr = str_replace('(', '', $tagStr);
                        $tagStr = str_replace(')', '', $tagStr);
                        $timeStamp = strtotime($dateStr);

                        if ($latestTimeStamp < $timeStamp) {
                            $latestTimeStamp = $timeStamp;
                            $latestTag = $tagStr;
                        }
                    }
                }
                if ($latestTag) {
                    $latestTag = str_replace('tag:', '', $latestTag);

                    $tagParts = explode('.', $latestTag);

                    if (count($tagParts) !== 3) {
                        return false;
                    }
                    $this->_latest_tag = [
                        'tagstring' => $latestTag,
                        'tagparts' => $tagParts,
                        'timestamp' => $latestTimeStamp, ];
                } else {
                    $this->_latest_tag = false;
                }
            }
        }
        return $this->_latest_tag;
    }

    /**
     * git command used: //git log 0.0.1..HEAD --oneline
     * return @string (major | minor | patch)
     */
    public function getChangeTypeSinceLastTag()
    {
        $latestTag = trim($this->getLatestTag()['tagstring']);

        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            //var_dump ($git);
            //die();

            $options = [
                'oneline' => true,
            ];

            $cwd = getcwd();
            chdir($this->Directory);

            try {
                $result = $git->log($latestTag . '..HEAD', $options);
                // print_r($latestTag);
                // print_r($result);
                if (! is_array($result)) {
                    $result = explode("\n", $result);
                }
                // print_r ($result);
            } catch (Exception $e) {
                $errStr = $e->getMessage();
                GeneralMethods::output_to_screen('Unable to get next tag type (getChangeTypeSinceLastTag): ' . $errStr);
                return false;
            }

            chdir($cwd);
            $returnLine = 'PATCH';

            foreach ($result as $line) {
                if (stripos($line, 'MAJOR:') !== false) {
                    $returnLine = 'MAJOR';
                    break;
                }
                if (stripos($line, 'MINOR:') !== false) {
                    $returnLine = 'MINOR';
                }
            }
            return $returnLine;
        }
    }

    public function createTag($tagCommandOptions)
    {
        $this->gitRepo->tag($tagCommandOptions);
        $this->gitRepo->push(['tags' => true]);
    }

    public function updateGitHubInfo($array)
    {
        // see https://developer.github.com/v3/repos/#edit

        # not working

        $defaultValues = [
            'name' => $this->LongModuleName(),
            'private' => false,
            'has_wiki' => false,
            'has_issues' => true,
            'has_downloads' => true,
            'homepage' => 'http://ssmods.com/',
        ];

        if ($this->Description) {
            $array['description'] = $this->Description;
        }

        foreach ($defaultValues as $key => $value) {
            if (! isset($array[$key])) {
                $array[$key] = $value;
            }
        }

        GeneralMethods::output_to_screen('updating Git Repo information ...');

        $this->gitApiCall($array, '', 'PATCH');
    }

    public function addRepoToScrutinzer()
    {
        Scrutizer::send_to_scrutinizer(
            $this->Config()->get('scrutinizer_api_key'),
            $this->Config()->get('github_user_name'),
            $this->ModuleName
        );
    }

    protected function IsDirGitRepo($directory)
    {
        return file_exists($directory . '/.git');
    }

    protected function gitApiCall($data, $gitAPIcommand = '', $method = 'GET')
    {
        $obj = new GitHubApi();
        $obj->gitApiCall($this->moduleName, $data, $gitAPIcommand, $method);
    }

    /*
     * This function is just used to suppression of warnings
     * */
    private function catchFopenWarning($errno, $errstr)
    {
    }
}
