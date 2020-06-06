<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use Sunnysideup\ModuleChecks\BaseCommands\RunCommandLineMethodOnModule;

class FixConfigBasics extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'mv ./config ./_config',
    ];
}
