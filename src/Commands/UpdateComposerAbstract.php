<?php

namespace Sunnysideup\ModuleChecks\Commands;

use Sunnysideup\ModuleChecks\Api\ComposerJsonClass;

abstract class UpdateComposerAbstract extends BaseCommand
{
    protected $composerJsonObj = null;

    private static $enabled = false;

    public function __construct($repo)
    {
        parent::__construct($repo);
        $this->composerJsonObj = new ComposerJsonClass($this->repo);
    }

    abstract public function run();

    abstract public function getDescription(): string;

    public function getError(): string
    {
        return 'Could not update composer';
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return $this->composerJsonObj->getJsonData();
    }

    /**
     * @param array $array [description]
     */
    protected function setJsonData(array $array)
    {
        $this->composerJsonObj->setJsonData($array);
    }
}
