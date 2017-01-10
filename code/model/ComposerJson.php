<?php

class ComposerJson extends Object
{
    public function ComposerJson($gitHubModuleInstance)
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


        return (isset($this->jsonData));
    }

    public function updateJsonData()
    {
        if (! isset($this->jsonData)) {
            $this->readJsonFromFile();
        }
        

        if (isset($this->jsonData)) {
            GeneralMethods::outputToScreen("<li> Updating composer.json </li>");
            $composerUpdates = ClassInfo::subclassesFor('UpdateComposer');
            array_shift($composerUpdates);

            $limitedComposerUpdates = $this->Config()->get('updates');
            
            if ($limitedComposerUpdates && count($limitedComposerUpdates)) {
                $composerUpdates = array_intersect($composerUpdates, $limitedComposerUpdates);
            }


            foreach ($composerUpdates as $composerUpdate) {
                $obj = $composerUpdate::create($this);
                $obj->run();
            }

            $this->writeJsonToFile();
        } else {
            GeneralMethods::outputToScreen('<li style = "color: red;"> ' . $this->moduleName. '  has no composer.json !!!</li>');
            //UpdateModules::$unsolvedItems[$this->moduleName] = 'No composer.json';
            
            UpdateModules::addUnsolvedProblem($this->moduleName, 'No composer.json');
        }
    }

    protected function checkOrAddExtra() {
		
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

        return $this->jsonData->description;
    }


    private function catchFopenWarning()
    {
    }
}
