<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\ShellCommands;

use Sunnysideup\ModuleChecks\BaseCommands\RunCommandLineMethodOnModule;

class RemoveOrig extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'find . -type f -name "*.orig" -exec rm  -Rf "{}" \;',
    ];
}
