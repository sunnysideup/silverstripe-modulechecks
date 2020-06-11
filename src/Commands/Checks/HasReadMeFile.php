<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class HasReadMeFile extends ChecksAbstract
{
    protected $options = [
        'README.md',
        'README.MD',
        'readme.md',
    ];

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
        foreach ($this->options as $option) {
            $outcome = $this->hasFileOnGitHub($option);
            if ($outcome) {
                return true;
            }
        }
        return $this->hasError();
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Does the module have a README file in root';
    }
}
