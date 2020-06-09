<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class ExistsOnAddOns extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * @return boolean
     */
    public function run(): bool
    {
        $name = $this->getName();
        $packagistUserName = $this->Config()->get('packagist_user_name');

        return $this->checkLocation(
            'http://addons.silverstripe.org/add-ons/' .
            $packagistUserName .
            '/' . $name
        );
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Does the module exist on addons.silverstripe.org?';
    }
}
