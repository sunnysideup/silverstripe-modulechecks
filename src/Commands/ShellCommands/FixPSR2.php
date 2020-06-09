<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class FixPSR2 extends ShellCommandsAbstract
{
    protected $commands = [
        'dir=src sslint-ecs > errorsToFixECS.txt',
        'dir=src level=1 sslint-stan > errorsToFixStan.txt',
    ];

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
        return 'Run coding standards';
    }
}
