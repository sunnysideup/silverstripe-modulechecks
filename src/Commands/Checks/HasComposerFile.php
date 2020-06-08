<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class HasComposerFile extends ChecksAbstract
{
    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        return $this->hasFileOnGitHub('composer.json');
    }


}
