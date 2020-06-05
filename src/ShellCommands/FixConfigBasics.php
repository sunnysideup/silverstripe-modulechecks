<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use RunCommandLineMethodOnModule;

class FixConfigBasics extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'mv ./config ./_config',
    ];
}
