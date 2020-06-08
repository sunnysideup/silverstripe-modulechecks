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

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Does the module have a LICENSE?';
    }


}
