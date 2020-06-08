<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class ExistsOnAddOns extends ChecksAbstract
{
    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        $name = $this->getName();
        $packagpackagistUserName = $this->Config()->get('packagist_user_name');

        return GeneralMethods::check_location(
            'http://addons.silverstripe.org/add-ons/' .
            $packagistUserName .
            '/' . $name
        );
    }


}
