<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Model\GitHubModule;

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

        $modules = GitHubApi::get_all_repos();

        foreach ($modules as $module) {
            GitHubModule::get_or_create_github_module($module);
        }
    }
}
