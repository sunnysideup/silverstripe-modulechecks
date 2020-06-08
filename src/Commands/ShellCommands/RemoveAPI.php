<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveAPI extends ShellCommandsAbstract
{
    private static $enabled = false;

    protected $commands = [
        'rm ./docs/api/ -Rf',
    ];
}
