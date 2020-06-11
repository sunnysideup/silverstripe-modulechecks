<?php

namespace Sunnysideup\ModuleChecks\Commands;
use Sunnysideup\ModuleChecks\Commands\BaseCommand;

abstract class LastAbstract extends BaseCommand
{
    private static $enabled = false;

    abstract public function run(): bool;

    abstract public function getDescription(): string;

    public function getError(): string
    {
        return 'Could not tear down module';
    }
}
