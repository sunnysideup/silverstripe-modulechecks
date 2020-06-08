<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class HasLicense extends ChecksAbstract
{
    /**
     * @return boolean
     */
    protected function run() :bool
    {
        return $this->hasFileOnGitHub('LICENSE');
    }


}
