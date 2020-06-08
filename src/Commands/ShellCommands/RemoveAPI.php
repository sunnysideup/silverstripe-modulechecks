<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveAPI extends ShellCommandsAbstract
{
    protected $commands = [
        'rm ./docs/api/ -Rf',
    ];
}
