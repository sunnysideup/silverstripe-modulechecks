<?php

namespace Sunnysideup\ModuleChecks\Commands;

use Sunnysideup\ModuleChecks\BaseObject;

class BaseCommand extends BaseObject
{
    protected $repo = null;

    private static $enabled = false;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function getError(): string
    {
        user_error('Please implement on command ' . __CLASS__);
    }

    protected function getName(): string
    {
        return $this->repo->ModuleName;
    }
}
