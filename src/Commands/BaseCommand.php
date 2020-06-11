<?php

namespace Sunnysideup\ModuleChecks\Commands;

use Sunnysideup\ModuleChecks\BaseObject;

class BaseCommand extends BaseObject
{
    protected $repo = null;

    protected $errorString = '';

    private static $enabled = false;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function getError(): string
    {
        return $this->errorString;
    }

    protected function getName(): string
    {
        return $this->repo->ModuleName;
    }

    protected function logError(string $error)
    {
        if (trim($error)) {
            ModuleCheck::log_error($error);
            $this->errorString = " \n|" . $error;
        }
    }

    protected function hasError(?bool $error = false)
    {
        if ($error) {
            return false;
        }
        return trim($this->errorString) ? true : false;
    }
}
