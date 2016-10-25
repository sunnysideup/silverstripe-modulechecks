<?php

Class ConfigYML extends Object {

    public function ConfigYML($gitHubModuleInstance) {
        if (! $gitHubModuleInstance) {
            user_error ("CheckComposerJson needs an instance of GitHubModule");
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName;
    }

    private function readYMLFromFile() {
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        $filename = $folder . '/' . $this->moduleName . '/_config/config.yml';

        $this->yaml_data = yaml_parse_file  ($filename);

        return ($this->yaml_data);

    }

    private function catchFopenWarning() {

    }

}
