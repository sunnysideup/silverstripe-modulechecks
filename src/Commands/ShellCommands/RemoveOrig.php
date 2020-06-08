<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveOrig extends ShellCommandsAbstract
{
    protected $commands = [
        'find . -type f -name "*.orig" -exec rm  -Rf "{}" \;',
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
    public function getDescription() : string
    {
        return 'Remove *.orig files';
    }
}
