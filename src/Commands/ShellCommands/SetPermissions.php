<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class SetPermissions extends ShellCommandsAbstract
{
    protected $commands = [
        'find ./ -type f -exec chmod 644 {} \;',
        'find . -type d -exec chmod 755 {} \;',
    ];

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = false;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Reset permissions';
    }
}
