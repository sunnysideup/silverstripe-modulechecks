<?php

namespace Sunnysideup\ModuleChecks\Commands;

use Sunnysideup\ModuleChecks\Api\ComposerJsonClass;
use Sunnysideup\ModuleChecks\Model\Module;

abstract class UpdateComposerAbstract extends BaseCommand
{
    protected $composerJsonObj = null;

    private static $enabled = false;

    public function __construct(?Module $repo = null)
    {
        parent::__construct($repo);
        if($this->repo) {
            $this->composerJsonObj = new ComposerJsonClass($this->repo);
        }
    }

    abstract public function run(): bool;

    abstract public function getDescription(): string;

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return $this->composerJsonObj->getJsonData();
    }

    /**
     * @param array $array
     */
    protected function setJsonData(array $array)
    {
        $this->composerJsonObj->setJsonData($array);
    }
}
