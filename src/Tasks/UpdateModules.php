<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Model\CheckPlan;

/**
 * main class running all the updates
 */
class UpdateModules extends BuildTask
{
    public static $unsolvedItems = [];

    protected $enabled = true;

    protected $title = 'Update Modules';

    protected $description = '
        Adds files necessary for publishing a module to GitHub.
        The list of modules is specified in standard config or else it retrieves a list of modules from GitHub.';

    public function run($request)
    {
        Environment::increaseTimeLimitTo(86400);

        set_error_handler('errorHandler', E_ALL);
        $sanityCount = 0;
        $obj = CheckPlan::get_next_module_check();
        while ($obj && $sanityCount < 99999) {
            $obj->run();
            $sanityCount++;
            $obj = CheckPlan::get_next_module_check();
        }

        restore_error_handler();
    }

    protected function errorHandler(int $errno, string $errstr)
    {
        $message = 'There was an error ' . $errstr . ' (' . $errno . ')';

        ModuleCheck::log_error($message);

        return true;
    }
}
