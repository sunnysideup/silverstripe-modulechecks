<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\RunCommandLineMethodOnModule;

class RemoveOrig extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'find . -type f -name "*.orig" -exec rm  -Rf "{}" \;',
    ];
}
