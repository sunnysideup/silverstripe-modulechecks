<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\RunCommandLineMethodOnModule;

class RemoveSVN extends RunCommandLineMethodOnModule
{
    protected $commands = [
        ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;',
    ];
}
