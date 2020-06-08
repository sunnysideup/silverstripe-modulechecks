<?php
namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemovePHPDox extends ShellCommandsAbstract
{
    protected $command = [
        'rm ./docs/en/phpdox/ -rf',
        'rm ./docs/phpdox/ -rf',
        'rm ./docs/phpdox/ -rf',
        'rm ./docs/api/ -rf',
        'rm ./docs/build/ -rf ',
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
        return 'Remove phpdox files';
    }
}
