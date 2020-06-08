<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class FixConfigBasics extends ShellCommandsAbstract
{
    protected $commands = [
        'mv ./config ./_config',
    ];
}
