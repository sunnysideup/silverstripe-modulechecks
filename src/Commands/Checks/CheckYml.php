<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Api\ConfigYML;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class CheckYml extends ChecksAbstract
{
    /**
     * @return boolean
     */
    public function run(): bool
    {
        return ConfigYML::create($this->repo)->reWrite();
    }
}
