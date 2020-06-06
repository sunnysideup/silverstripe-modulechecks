<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\RunCommandLineMethodOnModule;

class RemoveAPI extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'rm ./docs/api/ -Rf',
    ];
}
