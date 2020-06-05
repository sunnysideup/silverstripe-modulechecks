<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use Sunnysideup\ModuleChecks\Api\RunCommandLineMethodOnModule;

class RemoveOrig extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'find . -type f -name "*.orig" -exec rm  -Rf "{}" \;',
    ];
}
