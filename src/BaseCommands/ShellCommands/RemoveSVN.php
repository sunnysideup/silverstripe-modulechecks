<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\ShellCommands;

use Sunnysideup\ModuleChecks\BaseCommands\RunCommandLineMethodOnModule;

class RemoveSVN extends RunCommandLineMethodOnModule
{
    protected $commands = [
        ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;',
    ];
}
