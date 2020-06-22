<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Model\CheckPlan;
use Sunnysideup\ModuleChecks\Model\ModuleCheck;

/**
 * main class running all the updates
 */
class RunCheckPlan extends BuildTask
{

    protected $enabled = true;

    protected $title = 'Update Modules';

    protected $description = '
        Adds files necessary for publishing a module to GitHub.
        The list of modules is specified in standard config or else it retrieves a list of modules from GitHub.';

    private static $segment = 'run-check-plan';

    public function run($request)
    {
        Environment::increaseTimeLimitTo(86400);

        $sanityCount = 0;
        $checkPlanID = $_GET['checkplanid'] ?? 0;
        $moduleID = $_GET['moduleid'] ?? 0;
        $moduleCheckID = $_GET['modulecheckid'] ?? 0;
        $obj = CheckPlan::get_next_module_check($checkPlanID, $moduleID, $moduleCheckID);
        echo '<h1>++++++++++++ STARTING +++++++++++++++</h1>';
        while ($obj && $sanityCount < 99999) {
            echo '<h1>ERROR</h1>';
            // set_error_handler([$this, 'errorHandler'], E_ALL);
            echo 'running ' . $obj->ID;
            $obj->run();
            // restore_error_handler();
            $sanityCount++;
            $obj = CheckPlan::get_next_module_check($checkPlanID, $moduleID, $moduleCheckID);
        }

        echo '<h1>++++++++++++ DONE +++++++++++++++</h1>';
    }

    public function errorHandler(int $errno, string $errstr)
    {
        $message = 'There was an error ' . $errstr . ' (' . $errno . ')';

        ModuleCheck::log_error($message);

        return true;
    }
}
