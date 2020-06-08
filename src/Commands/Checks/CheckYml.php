<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Api\ConfigYML;

class CheckYml extends ChecksAbstract
{
    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        return ConfigYML::create($this->repo)->reWrite();
    }
}
