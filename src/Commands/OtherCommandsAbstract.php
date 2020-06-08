<?php

namespace Sunnysideup\ModuleChecks\Commands;

abstract class OtherCommandsAbstract extends BaseObject
{

    private static $enabled = false;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    abstract public function run();

    abstract public function description() : string;
}
