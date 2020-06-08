<?php

namespace Sunnysideup\ModuleChecks\Api;

use Exception;



use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
use Yaml;
use Sunnysideup\ModuleChecks\BaseObject;

class ConfigYML extends BaseObject
{

    protected $moduleName = '';
    protected $repo = null;
    protected $ymlData = null;
    protected $filename = '';

    public function __construct($gitHubModuleInstance, $fileToCheck = 'config.yml')
    {
        if (! $gitHubModuleInstance) {
            user_error('ConfigYML needs an instance of GitHubModule');
        }
        $this->repo = $gitHubModuleInstance;
        $this->ymlData = null;
        $folder = GitHubModule::Config()->get('absolute_temp_folder');

        $this->filename = $gitHubModuleInstance->Directory() . '/_config/' . $fileToCheck;
    }

    public function reWrite()
    {
        if (! $this->readYMLFromFile()) {
            return false;
        }
        if (! $this->writeYAMLToFile()) {
            return false;
        }
        return true;
    }

    public function readYMLFromFile()
    {
        GeneralMethods::output_to_screen('reading config yml ...  ', 'updating');

        if (! file_exists($this->filename)) {
            GeneralMethods::output_to_screen('<li>Unable to load: ' . $this->filename, 'updated');
            //UpdateModules::$unsolvedItems[$this->gitHubModuleInstance->ModuleName] = "Unable to load " . $this->filename;

            UpdateModules::addUnsolvedProblem($this->repo->ModuleName, 'Unable to load ' . $this->filename);
            return false;
        }

        try {
            $this->ymlData = Yaml::parse(file_get_contents($this->filename));
        } catch (Exception $e) {
            GeneralMethods::output_to_screen('<li>Unable to parse the YAML string: ' . $e->getMessage() . ' <li>', 'updated');

            //UpdateModules::$unsolvedItems[$this->gitHubModuleInstance->ModuleName] = "Unable to parse the YAML string: " .$e->getMessage();

            UpdateModules::addUnsolvedProblem($this->repo->ModuleName, 'Unable to parse the YAML string: ' . $e->getMessage());

            //trigger_error ("Error in YML file");

            $this->replaceFaultyYML();

            return false;
        }

        return $this->ymlData;
    }

    public function replaceFaultyYML()
    {
        return false;

        /**function broken do not use**/

        if (file_exists($this->filename)) {
            $rawYML = file_get_contents($this->filename);

            $lines = explode("\n", $rawYML);

            $replacment = '';

            foreach ($lines as $index => $line) {
                if (strpos($line, 'After:') !== false) {
                    $replacment = 'After:';
                    $listitems = explode(',', $line);
                    //print_r ($listitems);
                    foreach ($listitems as $item) {
                        if (! trim($item)) {
                            continue;
                        }

                        $item = str_replace('After: ', '', $item);
                        $replacment .= '  - ' . trim($item) . '';
                    }
                    $lines[$index] = $replacment;
                }
            }
            $newYML = implode('', $lines);

            GeneralMethods::output_to_screen('Updating config.YML to correct syntax ... ', 'updating');

            // $file = fopen($this->filename, 'w');

            file_put_contents($this->filename, $newYML);
        } else {
            return false;
        }
    }

    public function writeYAMLToFile()
    {
        GeneralMethods::output_to_screen('Writing config yml ... ', 'updating');

        if (! $this->ymlData) {
            return false;
        }

        $yaml = Yaml::dump($this->ymlData);
        file_put_contents($this->filename, $yaml);
        return true;
    }

    private function catchFopenWarning()
    {
    }
}
