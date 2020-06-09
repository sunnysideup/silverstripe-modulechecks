<?php

namespace Sunnysideup\ModuleChecks\Commands\ShellCommands;

use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;

class RemoveAllBranches extends ShellCommandsAbstract
{
    protected $commands = [
        'git tag -d 2.4',
        'git push origin :refs/tags/2.4',

        'git tag -d 3.0',
        'git push origin :refs/tags/3.0',

        'git tag -d 3.1',
        'git push origin :refs/tags/3.1',

        'git branch -a  | grep -v master | grep "remotes/origin/"  | cut -d "/" -f 3- | xargs -n 1 git push --delete origin',
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
        return 'Remove all branches';
    }
}
