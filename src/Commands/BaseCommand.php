<?php

namespace Sunnysideup\ModuleChecks\Commands;

use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Model\Check;
use Sunnysideup\ModuleChecks\Model\Module;
use Sunnysideup\ModuleChecks\Model\ModuleCheck;
use ReflectionClass;
use Sunnysideup\Flush\FlushNow;
use SilverStripe\Core\ClassInfo;

abstract class BaseCommand
{
    protected $repo = null;

    protected $errorString = '';

    private static $enabled = false;


    public function __construct(?Module $repo = null)
    {
        $this->repo = $repo;
        if($repo) {
            $this->rootDirForModule = $this->repo->Directory();
        }
    }
    abstract public function run(): bool;

    public function calculateType(): string
    {
        $list = class_parents($this);
        foreach ($list as $class) {
            $abstractClass = new ReflectionClass($class);
            if ($abstractClass->isAbstract()) {
                return ClassInfo::shortName($class);
            }
        }

        return 'error';
    }

    public function setRepo($repo)
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

    protected function getNameWithoutSilverstripe(): string
    {
        $str = $this->repo->ModuleName;
        foreach(['silverstripe-', 'silverstripe_'] as $prefix) {
            if (substr($str, 0, strlen('silverstripe-')) == $prefix) {
                $str = substr($str, strlen($prefix));
            }
        }
        return $str;
    }

    protected function logError(string $error)
    {
        if (trim($error)) {
            FlushNow::do_flush('Error --- '.$error);
            $this->errorString .= " \n|" . $error;
        }
    }

    protected function hasError(): bool
    {
        return trim($this->errorString) ? true : false;
    }

}
