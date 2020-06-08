<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveAPI extends ShellCommandsAbstract
{
    protected $commands = [
        'rm ./docs/api/ -Rf',
    ];

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = false;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Remove api folder';
    }

}
