<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\RunCommandLineMethodOnModule;

class SetPermissions extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'find ./ -type f -exec chmod 644 {} \;',
        'find . -type d -exec chmod 755 {} \;',
    ];
}
