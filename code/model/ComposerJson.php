<?php

class ComposerJson extends Object {

    public function ComposerJson($gitHubModuleInstance) {
        if (! $gitHubModuleInstance) {
            user_error ("CheckComposerJson needs an instance of GitHubModule");
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName;
    }

    private function readJsonFromFile() {
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/composer.json'; 

        $file = fopen ($filename, 'r');
        if ($file) {
            $json = fread($file, filesize($filename));
            $array = json_decode ($json);
            $this->jsonData = $array;
            print_r ($array);
            die();
        }
        fclose ($file);

        return (isset($array));

    }

    public function updateJsonData() {

        if ( ! isset($this->jsonData)) {
            $this->readJsonFromFile();
        }
        

        $composerUpdates = ClassInfo::subclassesFor('ComposerJsonUpdate');

        foreach ($composerUpdates as $composerUpdate) {
                $obj = $composerUpdate::create($this);
                $obj->run(); 
        }

        $this->writeJsonToFile();
    }

    private function writeJsonToFile() {

        if ( ! $this->jsonData) { //if not loaded
            return false;
        }
        
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->gitHubModuleInstance->moduleName . '/composer.json';

        $file = fopen ($filename, 'w');
        if ($file) {
            fwrite ($file, json_encode($this->jsonData));
        }
        fclose ($file);
        return true;
    }
}
