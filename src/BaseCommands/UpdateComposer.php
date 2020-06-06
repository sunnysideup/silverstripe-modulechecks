<?php

namespace Sunnysideup\ModuleChecks\BaseCommands;

abstract class UpdateComposer
{
    protected $composerJsonObj = null;

    public function __construct($composerJsonObj)
    {
        $this->composerJsonObj = $composerJsonObj;
        if (! $this->composerJsonObj->getJsonData()) {
            user_error('No Json data!');
        }
    }

    abstract public function run();

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
