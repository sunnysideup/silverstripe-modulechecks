<?php

namespace Sunnysideup\ModuleChecks\Api;

use SilverStripe\Core\ClassInfo;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;


use Exception;
use SilverStripe\Assets\Filesystem;

use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\Exception\GitException;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataObject;
use SilverStripe\Core\Config\Config;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Model\GitHubModule;

class GitApi extends BaseObject
{

    private $repo = null;

    protected $gitApiWrapper = null;

    protected $commsWrapper = null;

    private $latestTag = null;

    public function __construct(GitHubModule $repo, ?bool $forceNew = false)
    {
        $this->repo = $repo;
        //check if one has been created already...
        if (! $this->gitApiWrapper) {
            //basic check
            if ($this->repo->ModuleName === '') {
                user_error('ModuleName element must be set before using git repository commands');
            }

            //create comms
            $this->commsWrapper = new GitWrapper();

            // Stream output of subsequent Git commands in real time to STDOUT and STDERR.
            if (Director::is_cli()) {
                $this->commsWrapper->streamOutput();
            }

            if (! Config::inst()->get(BaseObject::class, 'path_to_private_key')) {
                user_error('We recommend you set private key');
            }
            // Optionally specify a private key other than one of the defaults.
            $this->commsWrapper->setPrivateKey(Config::inst()->get(BaseObject::class, 'path_to_private_key'));

            //if directory exists, return existing repo,
            //otherwise clone it....
            if ($this->repo->IsDirGitRepo($this->repo->Directory())) {
                if ($forceNew) {
                    $this->repo->removeClone();
                    return $this->__construct($this->repo, false);
                }
                $this->gitApiWrapper = $this->commsWrapper->workingCopy($this->repo->Directory());
            } else {
                GeneralMethods::output_to_screen('cloning ... ' . $this->repo->fullGitURL(), 'created');

                $this->gitApiWrapper = null;
                $cloneAttempts = 0;
                while (! $this->gitApiWrapper) {
                    $cloneAttempts ++;
                    if ($cloneAttempts === 4) {
                        user_error('Failed to clone module ' . $this->repo->LongModuleName() . ' after ' . ($cloneAttempts - 1) . ' attemps.', E_USER_ERROR);
                        //UpdateModules::$unsolvedItems[$this->ModuleName()] = 'Failed to clone modules';
                        UpdateModules::addUnsolvedProblem($this->repo->ModuleName(), 'Failed to clone modules');
                    }
                    try {
                        $this->commsWrapper->setTimeout(240); //Big modules need a longer timeout
                        $this->gitApiWrapper = $this->commsWrapper->cloneRepository(
                            $this->repo->fullGitURL(),
                            $this->repo->Directory()
                        );
                        $this->commsWrapper->setTimeout(60);
                    } catch (Exception $e) {
                        if (strpos($e->getMessage(), 'already exists and is not an empty directory') !== false) {
                            user_error($e->getMessage(), E_USER_ERROR);
                        }

                        GeneralMethods::output_to_screen('<li>Failed to clone repository: ' . $e->getMessage() . '</li>');
                        GeneralMethods::output_to_screen('<li>Waiting 8 seconds to try again ...: </li>');
                        $this->repo->removeClone();
                        sleep(8);
                    }
                }
            }
            $this->gitApiWrapper->config('push.default', 'simple');
            $this->gitApiWrapper->config('user.name', Config::inst()->get(BaseObject::class, 'github_user_name'));
            $this->gitApiWrapper->config('user.email', Config::inst()->get(BaseObject::class, 'github_user_email'));
            $this->commsWrapper->git('config -l');
        }
    }


    /**
     * pulls a git repo
     *
     * @return bool
     */
    public function pull()
    {
        if ($this->gitApiWrapper) {
            try {
                $this->gitApiWrapper->pull();
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
        if ($this->gitApiWrapper) {
            try {
                $this->gitApiWrapper->commit($message);
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
        GeneralMethods::output_to_screen('Adding new files to ' . $this->repo->ModuleName . ' ...  ', 'created');

        if ($this->gitApiWrapper) {
            try {
                $this->gitApiWrapper->add('.');
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
        GeneralMethods::output_to_screen('Pushing files to ' . $this->repo->ModuleName . ' ...  ', 'created');

        if ($this->gitApiWrapper) {
            $pushed = false;
            $pushAttempts = 0;
            while (! $pushed) {
                $pushAttempts ++;
                try {
                    $this->gitApiWrapper->push();
                    $pushed = true;
                } catch (Exception $e) {
                    if ($pushAttempts === 3) {
                        $this->gitApiWrapper->getOutput();
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


    public function getLatestCommitTime()
    {
        // equivalent to git log -1 --format=%cd .

        if ($this->gitApiWrapper) {
            $options = [
                'format' => '%cd',
                '1' => true,
            ];

            try {
                $result = $this->gitApiWrapper->log($options);
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
        if ($this->latestTag === null) {
            if ($this->gitApiWrapper) {
                $options = [
                    'tags' => true,
                    'simplify-by-decoration' => true,
                    'pretty' => 'format:%ai %d',
                ];

                $cwd = getcwd();
                chdir($this->repo->Directory());

                try {
                    $result = $this->gitApiWrapper->log($options);
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
                    $this->latestTag = [
                        'tagstring' => $latestTag,
                        'tagparts' => $tagParts,
                        'timestamp' => $latestTimeStamp, ];
                } else {
                    $this->latestTag = false;
                }
            }
        }
        return $this->latestTag;
    }

    /**
     * git command used: //git log 0.0.1..HEAD --oneline
     * return @string (major | minor | patch)
     */
    public function getChangeTypeSinceLastTag()
    {
        $latestTag = trim($this->getLatestTag()['tagstring']);

        if ($this->gitApiWrapper) {
            //var_dump ($git);
            //die();

            $options = [
                'oneline' => true,
            ];

            $cwd = getcwd();
            chdir($this->repo->Directory());

            try {
                $result = $this->gitApiWrapper->log($latestTag . '..HEAD', $options);
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
        $this->gitApiWrapper->tag($tagCommandOptions);
        $this->gitApiWrapper->push(['tags' => true]);
    }

}
