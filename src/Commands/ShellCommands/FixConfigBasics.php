<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\RunCommandLineMethodOnModule;

class FixConfigBasics extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'mv ./config ./_config',
    ];
}
