<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\ShellCommands;

use Sunnysideup\ModuleChecks\BaseCommands\RunCommandLineMethodOnModule;

class RemoveAPI extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'rm ./docs/api/ -Rf',
    ];
}
