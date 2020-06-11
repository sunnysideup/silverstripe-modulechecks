<?php

namespace Sunnysideup\ModuleChecks\Commands;

abstract class OtherCommandsAbstract extends BaseCommand
{
    private static $enabled = false;

    abstract public function run(): bool;

    abstract public function description(): string;

    public function getError(): string
    {
        return 'Could not run command.';
    }
}
