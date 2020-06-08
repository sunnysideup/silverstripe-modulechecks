<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class ExitsOnPackagist extends ChecksAbstract
{
    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        $name = $this->getName();
        $packagistUserName = $this->Config()->get('packagist_user_name');

        return GeneralMethods::check_location(
            'https://packagist.org/packages/' .
            $packagistUserName . '/'.$name
        );
    }


}
