<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class HasComposerFile extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * @return boolean
     */
    public function run(): bool
    {
        return $this->hasFileOnGitHub('composer.json');
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Does the module have a composer file?';
    }
}
