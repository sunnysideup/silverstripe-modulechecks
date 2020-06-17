<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Model\Module;
use Sunnysideup\ModuleChecks\Model\CheckPlan;
use Sunnysideup\Flush\FlushNow;

/**
 * main class running all the updates
 */
class DisableModulesBasedOnComposerType extends BuildTask
{
    protected $enabled = true;

    protected $title = 'Disable modules that do not need to be checked';

    protected $description = '
        Disable modules using the ComposerType value.';

    private static $segment = 'disable-modules-based-on-composer-type';

    private static $required_type = 'silverstripe-vendormodule';

    public function run($request)
    {
        Environment::increaseTimeLimitTo(600);
        $modules = Module::get();
        if($this->Config()->required_type) {
            FlushNow::do_flush('<h1>Removing modules that are not: '.$this->Config()->required_type.'</h1>');
            foreach($modules as $module) {
                Module::update_composer_data($module, true);
                $hasData = $module->ComposerType ? true : false;
                if($hasData && $module->ComposerType === $this->Config()->required_type) {
                    FlushNow::do_flush('Keeping ' . $module->ComposerName . ' - '.$module->ComposerType, 'created');
                } elseif($hasData) {
                    FlushNow::do_flush('Removing ' . $module->ComposerName . ' - '.$module->ComposerType, 'deleted');
                    $module->Disabled = true;
                    $module->write();
                } else {
                    FlushNow::do_flush('Not enough data for: ' . $module->ComposerName, 'deleted');
                }
            }
        } else {
            FlushNow::do_flush('No type set', 'deleted');
        }
        echo '<h1>++++++++++++ DONE +++++++++++++++</h1>';

    }
}
