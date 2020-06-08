<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class HasReadMeFile extends ChecksAbstract
{
    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        return $this->hasFileOnGitHub('README.md');
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Does the module have a README file?';
    }


}
