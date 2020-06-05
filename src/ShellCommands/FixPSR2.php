<?php

namespace Sunnysideup\ModuleChecks\ShellCommands;

use Director;
use RunCommandLineMethodOnModule;

class FixPSR2 extends RunCommandLineMethodOnModule
{
    public function __construct($rootDirForModule = '')
    {
        parent::__construct($rootDirForModule);
        $this->commands = [
            'cp ' . Director::baseFolder() . '/modulechecks/ecs.yml ./',
            'composer require --dev symplify/easy-coding-standard',
            'vendor/bin/ecs check app/src --fix > errorsToFix.txt',
        ];
    }
}
