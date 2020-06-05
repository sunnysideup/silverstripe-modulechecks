<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use RunCommandLineMethodOnModule;


class RemoveOrig extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'find . -type f -name "*.orig" -exec rm  -Rf "{}" \;'
    ];
}

