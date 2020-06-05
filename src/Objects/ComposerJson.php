<?php

namespace Sunnysideup\ModuleChecks\Objects;

use ClassInfo;
use GeneralMethods;
use UpdateModules;
use ViewableData;

/**
 * ### @@@@ START REPLACEMENT @@@@ ###
 * WHY: automated upgrade
 * OLD:  extends Object (ignore case)
 * NEW:  extends ViewableData (COMPLEX)
 * EXP: This used to extend Object, but object does not exist anymore. You can also manually add use Extensible, use Injectable, and use Configurable
 * ### @@@@ STOP REPLACEMENT @@@@ ###
 */
class ComposerJson extends ViewableData
{
    /**
     * @var array|null
     */
    protected $jsonData = null;

    public function __construct($gitHubModuleInstance)
    {
        if (! $gitHubModuleInstance) {
            user_error('CheckComposerJson needs an instance of GitHubModule');
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName;
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return $this->jsonData;
    }

    /**
     * @param array $array [description]
     */
    public function setJsonData(array $array)
    {
        $this->jsonData = $array;
    }

    public function updateJsonFile()
    {
        if (! is_array($this->jsonData)) {
            $this->readJsonFromFile();
        }

        if (is_array($this->jsonData)) {
            GeneralMethods::output_to_screen('<li> Updating composer.json </li>');
            $composerUpdates = ClassInfo::subclassesFor('UpdateComposer');

            //remove base class
            array_shift($composerUpdates);

            //get all updates and if they exists then get the ones that we need to do ...
            $limitedComposerUpdates = $this->Config()->get('updates');
            if ($limitedComposerUpdates === 'none') {
                $composerUpdates = [];
            } elseif (is_array($limitedComposerUpdates) && count($limitedComposerUpdates)) {
                $composerUpdates = array_intersect($composerUpdates, $limitedComposerUpdates);
            }

            foreach ($composerUpdates as $composerUpdate) {
                $obj = $composerUpdate::create($this);
                $obj->run();
            }
            if ($this->writeJsonToFile()) {
                GeneralMethods::output_to_screen('<li> Updated JSON </li>');
            } else {
                UpdateModules::addUnsolvedProblem($this->moduleName, 'Could not write JSON');
            }
        } else {
            //UpdateModules::$unsolvedItems[$this->moduleName] = 'No composer.json';

            UpdateModules::addUnsolvedProblem($this->moduleName, 'No composer.json');
        }
    }

    public function getDescription()
    {
        if (! is_array($this->jsonData)) { //if not loaded
            return 'no json data';
        }

        return isset($this->jsonData['description']) ? $this->jsonData['description'] : 'description tba';
    }

    protected function writeJsonToFile()
    {
        if (! is_array($this->jsonData)) { //if not loaded
            return false;
        }

        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->gitHubModuleInstance->ModuleName . '/composer.json';
        $value = file_put_contents($filename, json_encode($this->jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $value ? true : false;
    }

    private function readJsonFromFile()
    {
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/composer.json';
        set_error_handler([$this, 'catchFopenWarning'], E_WARNING);

        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: file_get_contents (case sensitive)
         * NEW: file_get_contents (COMPLEX)
         * EXP: Use new asset abstraction (https://docs.silverstripe.org/en/4/changelogs/4.0.0#asset-storage
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
        $json = file_get_contents($filename);
        restore_error_handler();
        if ($json) {
            $this->jsonData = json_decode($json, true);
        } else {
            UpdateModules::addUnsolvedProblem($this->moduleName, 'Could not open composer.json file...');
        }
        return is_array($this->jsonData);
    }

    private function catchFopenWarning()
    {
        user_error('Can not open composer file ....');
    }
}
