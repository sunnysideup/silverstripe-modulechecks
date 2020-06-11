<?php

namespace Sunnysideup\ModuleChecks\Api;

use Exception;



use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Model\ModuleCheck;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
use Yaml;

class ConfigYML extends BaseObject
{
    protected $moduleName = '';

    protected $repo = null;

    protected $ymlData = null;

    protected $filename = '';

    public function __construct($repo, $fileToCheck = 'config.yml')
    {
        $this->repo = $repo;

        $this->filename = $this->repo->Directory() . '/_config/' . $fileToCheck;
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
        FlushNow::flushNow('reading config yml ...  ', 'updating');

        if (! file_exists($this->filename)) {
            //UpdateModules::$unsolvedItems[$this->repo->ModuleName] = "Unable to load " . $this->filename;

            ModuleCheck::log_error('Unable to load: ' . $this->filename);
            return false;
        }

        try {
            $this->ymlData = Yaml::parse(file_get_contents($this->filename));
        } catch (Exception $e) {
            ModuleCheck::log_error('Unable to parse the YAML string: ' . $e->getMessage());

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

            FlushNow::flushNow('Updating config.YML to correct syntax ... ', 'updating');

            // $file = fopen($this->filename, 'w');

            file_put_contents($this->filename, $newYML);
        } else {
            return false;
        }
    }

    public function writeYAMLToFile()
    {
        FlushNow::flushNow('Writing config yml ... ', 'updating');

        if (! $this->ymlData) {
            return false;
        }

        $yaml = Yaml::dump($this->ymlData);
        file_put_contents($this->filename, $yaml);
        return true;
    }
}
