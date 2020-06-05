<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use Exception;
use FileSystem;




use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Api\AddFileToModule;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Api\GitRepoFinder;
use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;
use Sunnysideup\ModuleChecks\Objects\ComposerJson;
use Sunnysideup\ModuleChecks\Objects\ConfigYML;
use Sunnysideup\ModuleChecks\Objects\GitHubModule;

/**
 * main class running all the updates
 */
class LoadModules extends BuildTask
{

    protected $title = 'Load Modules';

    protected $description = 'Get all the modules from github.';

    public function run($request)
    {
        Environment::increaseTimeLimitTo(3600);

        //Get list of all modules from GitHub
        $gitUserName = $this->Config()->get('github_user_name');

        $modules = GitRepoFinder::get_all_repos();

        foreach ($modules as $count => $module) {
            $moduleObject = GitHubModule::get_or_create_github_module($module);
        }

    }

}
