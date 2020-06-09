<?php

namespace Sunnysideup\ModuleChecks\Admin;

use SilverStripe\Admin\ModelAdmin;
use Sunnysideup\ModuleChecks\Model\Check;
use Sunnysideup\ModuleChecks\Model\CheckPlan;
use Sunnysideup\ModuleChecks\Model\Module;
use Sunnysideup\ModuleChecks\Model\ModuleCheck;

class ModuleCheckModelAdmin extends ModelAdmin
{
    private static $menu_title = 'Checks';

    private static $url_segment = 'checks';

    private static $managed_models = [
        CheckPlan::class,
        Module::class,
        Check::class,
        ModuleCheck::class,
    ];
}
