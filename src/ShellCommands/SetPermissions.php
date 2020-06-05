<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use RunCommandLineMethodOnModule;


class SetPermissions extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'find ./ -type f -exec chmod 644 {} \;',
        'find . -type d -exec chmod 755 {} \;',
    ];
}

