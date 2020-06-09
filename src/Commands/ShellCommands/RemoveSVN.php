<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveSVN extends ShellCommandsAbstract
{
    protected $commands = [
        ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;',
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
    public function getDescription(): string
    {
        return 'Remove .svn files';
    }
}
