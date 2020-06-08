<?php
namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveOldReadMe extends ShellCommandsAbstract
{
    protected $commands = [
        'rm docs/en/README.old.md -rf'
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
