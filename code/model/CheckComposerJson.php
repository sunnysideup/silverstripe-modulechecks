<?php

class ComposerJson extends Object {

    public function CheckComposerJson($gitHubModuleInstance) {
        if (! $gitHubModuleInstance) {
            user_error ("CheckComposerJson needs an instance of GitHubModule");
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName();
    }

    private readJsonFromFile() {
        $folder = GitHubModule->Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/composer.json', 

        $file = $fopen ($filename, 'r');
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

    public updateJsonData() {

        if ( ! $this->jsonData) {
            $this->readJsonFromFile();
        }
        
        
        if (isset($this->jsonData['authors']['homepage'])) {
            unset($this->jsonData['authors']['homepage']);
        }

        $this->writeJsonToFile();
    }

    private writeJsonToFile() {

        if ( ! $this->jsonData) { //if not loaded
            return false;
        }
        
        $folder = GitHubModule->Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/composer.json', 

        $file = $fopen ($filename, 'w');
        if ($file) {
            fwrite ($file, json_encode($this->jsonData));
        }
        fclose ($file);
        return true;
    }
}
