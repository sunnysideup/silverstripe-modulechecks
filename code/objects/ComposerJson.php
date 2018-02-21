<?php

class ComposerJson extends Object
{
    /**
     *
     * @var array|null
     */
    protected $jsonData = null;

    public function __construct($gitHubModuleInstance)
    {
        if (! $gitHubModuleInstance) {
            user_error("CheckComposerJson needs an instance of GitHubModule");
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName;
    }

    private function readJsonFromFile()
    {
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/composer.json';

        set_error_handler(array($this, 'catchFopenWarning'), E_WARNING);
        $file = fopen($filename, 'r');
        restore_error_handler();
        if ($file) {
            $json = fread($file, filesize($filename));
            $array = json_decode($json);
            $this->jsonData = $array;
            fclose($file);
        }


        return (is_array($this->jsonData));
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
            GeneralMethods::outputToScreen("<li> Updating composer.json </li>");
            $composerUpdates = ClassInfo::subclassesFor('UpdateComposer');

            //remove base class
            array_shift($composerUpdates);

            //get all updates and if they exists then get the ones that we need to do ...
            $limitedComposerUpdates = $this->Config()->get('updates');
            if ($limitedComposerUpdates && count($limitedComposerUpdates)) {
                $composerUpdates = array_intersect($composerUpdates, $limitedComposerUpdates);
            }


            foreach ($composerUpdates as $composerUpdate) {
                $obj = $composerUpdate::create($this);
                $obj->run();
            }

            if(! $this->writeJsonToFile()) {
                UpdateModules::addUnsolvedProblem($this->moduleName, 'Could not write JSON');
            }
        } else {
            //UpdateModules::$unsolvedItems[$this->moduleName] = 'No composer.json';

            UpdateModules::addUnsolvedProblem($this->moduleName, 'No composer.json');
        }
    }


    protected function writeJsonToFile()
    {
        if (! $this->jsonData) { //if not loaded
            return false;
        }

        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->gitHubModuleInstance->ModuleName . '/composer.json';



        $file = fopen($filename, 'w');
        if ($file) {
            fwrite($file, json_encode($this->jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        fclose($file);

        return true;
    }

    public function getDescription()
    {
        if (! property_exists ($this, 'jsonData') || ! $this->jsonData) { //if not loaded
            return false;
        }

        return isset($this->jsonData['description']) ? $this->jsonData['description'] : 'description tba';
    }


    private function catchFopenWarning()
    {
    }
}
