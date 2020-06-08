<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class MoveReadMeToRightPlace extends ShellCommandsAbstract
{
    protected $commands = [
        'mv -vn docs/en/README.md docs/en/INDEX.md'
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
    public function getDescription() : string
    {
        return 'Remove old readme';
    }
}
