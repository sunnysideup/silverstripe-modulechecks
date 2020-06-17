<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use Sunnysideup\ModuleChecks\Model\CheckPlan;

/**
 * main class running all the updates
 */
class DeleteAllModuleChecks extends BuildTask
{
    protected $enabled = true;

    protected $title = 'Delete all uncompleted Module Checks';

    protected $description = '
        Get rid of all the Module Checks that have not been completed.';

    private static $segment = 'delete-all-module-checks';

    public function run($request)
    {
        DB::query('DELETE FROM "ModuleCheck" WHERE Completed = 0 OR RUNNING = 1;');
        echo '<h1>++++++++++++ DONE +++++++++++++++</h1>';

    }
}
