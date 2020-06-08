<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class HasTLDRFile extends ChecksAbstract
{

    protected $options = [
        'docs/en/INDEX.md',
        'docs/en/INDEX.MD',
        'docs/en/index.md',
    ];

    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        foreach($this->options as $option) {
            return $this->hasFileOnGitHub('docs/en/' . $option);
        }
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
