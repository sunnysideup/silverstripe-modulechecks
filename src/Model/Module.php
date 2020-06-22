<?php

namespace Sunnysideup\ModuleChecks\Model;

use GitWrapper\GitWorkingCopy;

use SilverStripe\Assets\Filesystem;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;
use Sunnysideup\ModuleChecks\Api\GitApi;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\Flush\FlushNow;
use Sunnysideup\CMSNiceties\Traits\CMSNicetiesTraitForCMSLinks;

class Module extends DataObject
{
    use FlushNow;
    use CMSNicetiesTraitForCMSLinks;


    protected $gitWrapper = null;

    private static $table_name = 'Module';

    private static $db = [
        'ModuleName' => 'Varchar(200)',
        'Description' => 'Varchar(300)',
        'ForksCount' => 'Int',
        'DefaultBranch' => 'Varchar(100)',
        'Private' => 'Boolean',
        'HomePage' => 'Varchar(100)',
        'Disabled' => 'Boolean',
        'ComposerName' => 'Varchar(100)',
        'ComposerType' => 'Varchar(100)',
    ];

    private static $has_many = [
        'ModuleChecks' => ModuleCheck::class,
    ];

    private static $many_many = [
        'ExcludedFromPlan' => CheckPlan::class,
    ];

    private static $belongs_many_many = [
        'IncludedInPlan' => CheckPlan::class,
    ];

    private static $summary_fields = [
        'ModuleName' => 'Name',
        'Description' => 'Description',
        'ForksCount' => 'Forks',
        'DefaultBranch' => 'Branch',
        'Disabled.Nice' => 'Disabled',
        'HomePage' => 'HomePage',
        'ComposerName' => 'Composer Name',
        'ComposerType' => 'Composer Type',
    ];

    private static $searchable_fields = [
        'ModuleName' => 'PartialMatchFilter',
        'Description' => 'PartialMatchFilter',
        'DefaultBranch' => 'PartialMatchFilter',
        'Private' => 'ExactMatchFilter',
        'Disabled' => 'ExactMatchFilter',
        'HomePage' => 'PartialMatchFilter',
        'ComposerName' => 'PartialMatchFilter',
        'ComposerType' => 'PartialMatchFilter',
    ];

    private static $indexes = [
        'ModuleName' => true,
    ];

    private static $casting = [
        'Title' => 'Varchar(255)',
        'Directory' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
    ];



    private static $primary_model_admin_class = ModuleCheckModelAdmin::class;

    public static function get_or_create_github_module(array $moduleDetails, ?bool $force): self
    {
        $moduleName = trim($moduleDetails['name']);
        unset($moduleDetails['name']);
        $filter = ['ModuleName' => $moduleName];
        $gitHubModule = Module::get()->filter($filter)->first();
        if (! $gitHubModule) {
            $gitHubModule = Module::create($moduleDetails);
        } else {
            foreach ($moduleDetails as $field => $value) {
                $gitHubModule->{$field} = $value;
            }
        }
        $gitHubModule->ModuleName = $moduleName;
        if(!$gitHubModule->ComposerType && $force) {
            self::update_composer_data($gitHubModule, false);
        }
        $gitHubModule->write();

        return $gitHubModule;
    }

    public static function update_composer_data(Module $gitHubModule, ?bool $write = false)
    {
        $composerDataString = GitHubApi::get_raw_file_from_github($gitHubModule, 'composer.json');
        $composerData = json_decode($composerDataString, true);
        $gitHubModule->ComposerType = $composerData['type'] ?? 'tba';
        $gitHubModule->ComposerName = $composerData['name'] ?? 'tba';
        if($write) {
            $gitHubModule->write();
        }
    }

    public function getDirectory(): string
    {
        return $this->Directory();
    }

    public function getTitle(): string
    {
        return $this->ModuleName;
    }

    /**
     * absolute path
     * @return string
     */
    public function Directory(): string
    {
        $tempFolder = BaseObject::absolute_path_to_temp_folder();
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
    public function getURL(): string
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
    public function LongModuleName(): string
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
    public function ShortModuleName(): string
    {
        return str_replace('silverstripe-', '', $this->ModuleName);
    }

    public function ShortUCFirstName(): string
    {
        $array = explode(['_', '-'], $this->ShortModuleName());

        $name = '';

        foreach ($array as $part) {
            $name .= ucfirst($part);
        }

        return $name;
    }

    public function ModuleNameFirstLetterCapital(): string
    {
        $shortName = $this->ShortModuleName();

        $firstLetterCapitalName = str_replace('_', ' ', $shortName);
        $firstLetterCapitalName = str_replace('-', ' ', $firstLetterCapitalName);

        return strtolower($firstLetterCapitalName);
    }

    /**
     * @var string
     */
    public function FullGitURL()
    {
        $username = Config::inst()->get(BaseObject::class, 'github_user_name');
        // $gitURL = Config::inst()->get(BaseObject::class, 'github_account_base_url');
        return 'git@github.com:/' . $username . '/' . $this->ModuleName . '.git';
    }

    public function getBranch(): string
    {
        return 'master';
    }

    /**
     * removes a cloned repo
     */
    public function removeClone(): bool
    {
        $dir = $this->Directory();
        // FlushNow::do_flush('Removing ' . $dir . ' and all its contents ...  ', 'created');
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
    public function getRawFileFromGithub(string $fileName): string
    {
        return GitHubApi::get_raw_file_from_github($this, $fileName);
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
    public function commit($message = 'PATCH: module clean-up'): bool
    {
        return $this->checkOrSetGitCommsWrapper()->commit($message);
    }

    /**
     * adds all files to a git repo
     * @return bool
     */
    public function add(): bool
    {
        return $this->checkOrSetGitCommsWrapper()->add();
    }

    /**
     * adds all files to a git repo
     *
     * @return bool
     */
    public function push(): bool
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
    public function getChangeTypeSinceLastTag(): string
    {
        return $this->checkOrSetGitCommsWrapper()->getChangeTypeSinceLastTag();
    }

    public function createTag($tagCommandOptions)
    {
        return $this->checkOrSetGitCommsWrapper()->createTag($tagCommandOptions);
    }

    public function findNextTag(array $tag, string $changeType): string
    {
        return $this->checkOrSetGitCommsWrapper()->findNextTag($tag, $changeType);
    }

    /**
     * @param bool (optional) $forceNew - create a new repo and ditch all changes
     * @return GitWorkingCopy Repo Object
     */
    public function checkOrSetGitCommsWrapper(?bool $forceNew = false) : GitApi
    {
        if ($this->gitWrapper === null) {
            $this->gitWrapper = GitApi::create($this, $forceNew);
        }

        return $this->gitWrapper;
    }
}
