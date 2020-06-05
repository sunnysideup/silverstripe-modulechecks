<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use RunCommandLineMethodOnModule;


class RemoveAPI extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'rm ./docs/api/ -Rf'
    ];
}

