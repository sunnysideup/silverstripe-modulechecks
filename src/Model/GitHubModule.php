<?php

namespace Sunnysideup\ModuleChecks\Model;

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
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;


class GitHubModule extends DataObject
{

    public static function get_or_create_github_module(array $moduleDetails) : self
    {
        $moduleName = trim($moduleName[$moduleDetails['name']]);
        $filter = ['ModuleName' => $moduleName];
        $gitHubModule = GitHubModule::get()->filter($filter)->first();
        if (! $gitHubModule) {
            $gitHubModule = GitHubModule::create($moduleDetails);
        } else {
            foreach($moduleDetails as $field =>$value) {
                $gitHubModule->$field = $value;
            }
        }
        $gitHubModule->write();

        return $gitHubModule;
    }



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

    private static $primary_model_admin_class = ModuleCheckModelAdmin::class;

    public function getDirectory()
    {
        return $this->Directory();
    }

    /**
     * absolute path
     * @return string
     */
    public function Directory() :string
    {
        $tempFolder = Config::inst()->get(BaseObject::class, 'absolute_temp_folder');
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
        return '';
    }

    /**
     * @var string
     */
    public function getURL() : string
    {
        return $this->URL();
    }

    /**
     * @var string
     */
    public function URL(): string
    {
        $username = Config::inst()->get(BaseObject::class, 'github_user_name');

        return 'https://github.com/' . $username . '/' . $this->ModuleName;
    }

    /**
     * @var string
     */
    public function LongModuleName() : string
    {
        return Config::inst()->get(BaseObject::class, 'github_user_name') . '/' . $this->ModuleName;
    }

    public function MediumModuleName()
    {
        return $this->ModuleName;
    }

    /**
     * @todo: check that silverstripe- is at the start of string.
     * @return string
     */
    public function ShortModuleName() : string
    {
        return str_replace('silverstripe-', '', $this->ModuleName);
    }

    public function ShortUCFirstName() : string
    {
        $array = explode(['_', '-'], $this->ShortModuleName());

        $name = '';

        foreach ($array as $part) {
            $name .= ucfirst($part);
        }

        return $name;
    }

    public function ModuleNameFirstLetterCapital() : string
    {
        $shortName = $this->ShortModuleName();

        $firstLetterCapitalName = str_replace('_', ' ', $shortName);
        $firstLetterCapitalName = str_replace('-', ' ', $firstLetterCapitalName);

        return strtolower($firstLetterCapitalName);
    }

    /**
     * @var string
     */
    public function fullGitURL()
    {
        $username = Config::inst()->get(BaseObject::class, 'github_user_name');
        // $gitURL = Config::inst()->get(BaseObject::class, 'github_account_base_url');
        return 'git@github.com:/' . $username . '/' . $this->ModuleName . '.git';
    }

    public function getBranch() : string
    {
        return 'master';
    }

    protected $gitWrapper = null;

    /**
     * @param bool (optional) $forceNew - create a new repo and ditch all changes
     * @return GitWorkingCopy Repo Object
     */
    protected function checkOrSetGitCommsWrapper(?bool $forceNew = false): GitWorkingCopy
    {
        if ($this->gitWrapper === null) {
            $this->gitWrapper = GitApi($this, $forceNew);
        }

        return $this->gitWrapper;
    }

    /**
     * removes a cloned repo
     */
    public function removeClone() : bool
    {
        $dir = $this->Directory();
        GeneralMethods::output_to_screen('Removing ' . $dir . ' and all its contents ...  ', 'created');
        $this->gitRepo = null;
        Filesystem::removeFolder($dir); // removes contents but not the actual folder
        //rmdir ($dir);
        return ! file_exists($dir);
    }

    /**
     * retrieves a raw file from Github
     *
     * @return string
     */
    public function getRawFileFromGithub(string $fileName) : string
    {
        $obj = new GitHubApi();

        return $obj->getRawFileFromGithub($this, $fileName);
    }



    protected function IsDirGitRepo($directory)
    {
        return file_exists($directory . '/.git');
    }



    /**
     * pulls a git repo
     *
     * @return bool
     */
    public function pull()
    {
        return $this->checkOrSetGitCommsWrapper()->pull();
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
        return $this->checkOrSetGitCommsWrapper()->commit($message);
    }

    /**
     * adds all files to a git repo
     * @return bool
     */
    public function add() : bool
    {
        return $this->checkOrSetGitCommsWrapper()->add();
    }

    /**
     * adds all files to a git repo
     *
     * @return bool
     */
    public function push() : bool
    {
        return $this->checkOrSetGitCommsWrapper()->push();
    }


    public function getLatestCommitTime()
    {
        return $this->checkOrSetGitCommsWrapper()->getLatestCommitTime();
    }

    public function getLatestTag()
    {
        return $this->checkOrSetGitCommsWrapper()->getLatestTag();
    }

    /**
     * git command used: //git log 0.0.1..HEAD --oneline
     * returns string (major | minor | patch)
     * @return string
     */
    public function getChangeTypeSinceLastTag() : string
    {
        return $this->checkOrSetGitCommsWrapper()->getChangeTypeSinceLastTag();
    }

    public function createTag($tagCommandOptions)
    {
        return $this->checkOrSetGitCommsWrapper()->createTag($tagCommandOptions);
    }


}
