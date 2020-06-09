<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class HasLicense extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Does the module have a LICENSE?';
    }

    /**
     * @return boolean
     */
    protected function run(): bool
    {
        return $this->hasFileOnGitHub('LICENSE');
    }
}
