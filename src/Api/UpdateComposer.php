<?php

namespace Sunnysideup\ModuleChecks\Api;

use ViewableData;



/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  extends Object (ignore case)
  * NEW:  extends ViewableData (COMPLEX)
  * EXP: This used to extend Object, but object does not exist anymore. You can also manually add use Extensible, use Injectable, and use Configurable
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
abstract class UpdateComposer extends ViewableData
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

