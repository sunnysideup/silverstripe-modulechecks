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

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Check that the yml files are in order';
    }
}
