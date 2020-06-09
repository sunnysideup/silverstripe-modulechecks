<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class FixConfigBasics extends ShellCommandsAbstract
{
    protected $commands = [
        'mv -vn ./config ./_config',
    ];

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Move config folder to _config';
    }
}
