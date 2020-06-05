<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use RunCommandLineMethodOnModule;


class RemoveSVN extends RunCommandLineMethodOnModule
{
    protected $commands = [
        ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;'
    ];
}

