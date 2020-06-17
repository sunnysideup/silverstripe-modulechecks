<?php

namespace Sunnysideup\ModuleChecks\Commands;

abstract class FirstAbstract extends BaseCommand
{
    private static $enabled = false;

    /**
     * always add if enabled
     * @var bool
     */
    private static $must_do = true;

    abstract public function run(): bool;

    abstract public function getDescription(): string;

    public function getError(): string
    {
        return 'Could not set up module';
    }
}
