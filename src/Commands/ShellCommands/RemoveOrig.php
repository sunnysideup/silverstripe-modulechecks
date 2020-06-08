<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveOrig extends ShellCommandsAbstract
{
    protected $commands = [
        'find . -type f -name "*.orig" -exec rm  -Rf "{}" \;',
    ];
}
