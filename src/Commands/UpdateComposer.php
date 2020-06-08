<?php

namespace Sunnysideup\ModuleChecks\Commands;

abstract class UpdateComposerAbstract extends BaseObject
{
    protected $composerJsonObj = null;

    private static $enabled = false;

    public function __construct($repo)
    {
        $this->repo = $repo;
        $this->composerJsonObj = $composerJsonObj;
        if (! $this->composerJsonObj->getJsonData()) {
            user_error('No Json data!');
        }
    }

    abstract public function run();

    abstract public function description() : string;

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
