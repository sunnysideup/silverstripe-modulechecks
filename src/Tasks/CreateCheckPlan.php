<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Model\CheckPlan;

/**
 * main class running all the updates
 */
class CreateCheckPlan extends BuildTask
{
    protected $enabled = true;

    protected $title = 'Create list of Checks to run';

    protected $description = '
        Get the current check plan and work out what needs doing!';

    private static $segment = 'create-check-plan';

    public function run($request)
    {
        Environment::increaseTimeLimitTo(600);
        $checkPlanID = $_GET['id'] ?? 0;
        $obj = CheckPlan::get_current_check_plan($checkPlanID);
        $obj->createChecks(true);
        echo '<h1>++++++++++++ DONE +++++++++++++++</h1>';

    }
}
