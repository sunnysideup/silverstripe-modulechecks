<?php
require ('../modulechecks/vendor/autoload.php');
use Symfony\Component\Yaml\Yaml;


Class ConfigYML extends Object {

    public function ConfigYML($gitHubModuleInstance) {
        if (! $gitHubModuleInstance) {
            user_error ("ConfigYML needs an instance of GitHubModule");
        }
        $this->gitHubModuleInstance = $gitHubModuleInstance;
        $this->moduleName = $gitHubModuleInstance->ModuleName;
        $this->yaml_data = null;
        $folder = GitHubModule::Config()->get('absolute_temp_folder');

        $this->filename = $folder . '/' . $this->moduleName . '/_config/config.yml';        
    }
    
    public function reWrite(){
		if (! $this->readYMLFromFile()) 
		{
			return false;
		}
		if (! $this->writeYAMLToFile()) 
		{
			return false;
		}
		return true;		
	}

    public function readYMLFromFile() {

		GeneralMethods::output_to_screen("reading config yml ...  ",'updating');
			
        try {
            $this->yaml_data = Yaml::parse(file_get_contents($this->filename));
            
            
            
            
        } catch (Exception $e) {
            GeneralMethods::output_to_screen("<li>Unable to parse the YAML string: " .$e->getMessage(). " <li>", 'updated') ;
            
            UpdateModules::$unsolvedItems[$this->gitHubModuleInstance->ModuleName] = "Unable to parse the YAML string: " .$e->getMessage();
            
			//trigger_error ("Error in YML file");
	
			return false;
        }


        return $this->yaml_data;

    }
    
    public function writeYAMLToFile() {
		
		GeneralMethods::output_to_screen("Writing config yml ... ",'updating');
		
		if (!$this->yaml_data) {
			return false;
		}
		
		$yaml = Yaml::dump($this->yaml_data);
		file_put_contents($this->filename, $yaml);
		//file_put_contents('/home/jack/test.yml', $yaml);
		return true;
	}

    private function catchFopenWarning() {

    }

}

