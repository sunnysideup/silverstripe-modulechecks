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

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Does the module have a composer file?';
    }

}
