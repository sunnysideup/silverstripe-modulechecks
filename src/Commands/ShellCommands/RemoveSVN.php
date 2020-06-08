<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveSVN extends ShellCommandsAbstract
{
    protected $commands = [
        ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;',
    ];
}
