<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Model\Module;

/**
 * main class running all the updates
 */
class LoadModules extends BuildTask
{
    protected $enabled = true;

    protected $title = 'Load Modules';

    protected $description = 'Get all the modules from github.';

    private static $segment = 'load-modules';

    public function run($request)
    {
        Environment::increaseTimeLimitTo(3600);
        $force = empty($_GET['force']) ? false : true;
        $modules = GitHubApi::get_all_repos();
        foreach ($modules as $name => $module) {
            Module::get_or_create_github_module($module, $force);
        }
        echo '<h1>++++++++++++ DONE +++++++++++++++</h1>';
    }
}
