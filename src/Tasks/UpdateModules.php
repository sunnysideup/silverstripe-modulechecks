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

    /**
     * e.g.
     * - moduleA
     * - moduleB
     * - moduleC
     *
     * @var array
     */
    private static $modules_to_update = [];

    /**
     * e.g.
     * - ClassNameForUpdatingFileA
     * - ClassNameForUpdatingFileB
     *
     * @var array
     */
    private static $files_to_update = [];

    /**
     * e.g.
     * - ClassNameForUpdatingFileA
     * - ClassNameForUpdatingFileB
     *
     * @var array
     */
    private static $commands_to_run = [];

    public function run($request)
    {
        Environment::increaseTimeLimitTo(86400);

        set_error_handler('errorHandler', E_ALL);

        while ($obj = CheckPlan::get_next_module_check()) {
            $obj->run();
        }

        restore_error_handler();

        $this->writeLog();
        //to do ..
    }

    protected function errorHandler(int $errno, string $errstr)
    {
        $message = 'There was an error ' . $errstr . ' (' . $errno . ')';

        ModuleCheck::log_error($message);

        return true;
    }
}
