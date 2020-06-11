<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class HasTLDRFile extends ChecksAbstract
{
    protected $options = [
        'INDEX.md',
        'INDEX.MD',
        'index.md',
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
            $outcome = $this->hasFileOnGitHub('docs/en/' . $option);
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
        return 'Does the module have a INDEX.md file in /docs/en?';
    }
}
