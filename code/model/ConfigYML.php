<?php
/*
use Symfony\Component\Yaml;


Class ConfigYML extends Object {

    public function ConfigYML($gitHubModuleInstance) {
        if (! $gitHubModuleInstance) {
            user_error ("ConfigYML needs an instance of GitHubModule");
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName;
    }

    public function readYMLFromFile() {

        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/_config/config.yml';

        try {
            $this->yaml_data = Yaml::parse(file_get_contents($folder));
        } catch (Exception $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
            return false;
        }


        return $this->yaml_data;

    }

    private function catchFopenWarning() {

    }

}
*/
