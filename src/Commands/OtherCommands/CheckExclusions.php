<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\BaseObject;

class CheckExclusions extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run($array): bool
    {
        $excludedWords = Config::inst()->get(BaseObject::class, 'excluded_words');

        if (count($excludedWords) > 0) {
            $folder = $this->repo->Directory();

            $results = $this->checkDirExcludedWords($folder . '/' . $this->repo->ModuleName, $excludedWords);

            if ($results && count($results > 0)) {
                $msg = '<h4>The following excluded words were found: </h4><ul>';
                foreach ($results as $file => $words) {
                    foreach ($words as $word) {
                        $msg .= "<li>${word} in ${file}</li>";
                    }
                }
                $msg .= '</ul>';

                //trigger_error ("excluded words found in files(s)");
                GeneralMethods::output_to_screen($msg);
                UpdateModules::$unsolvedItems[$this->repo->ModuleName] = $msg;
            }
        }
    }

    private function checkDirExcludedWords($directory, $wordArray): array
    {
        $filesAndFolders = scandir($directory);

        $problem_files = [];
        foreach ($filesAndFolders as $fileOrFolder) {
            if ($fileOrFolder === '.' || $fileOrFolder === '..' || $fileOrFolder === '.git') {
                continue;
            }

            $fileOrFolderFullPath = $directory . '/' . $fileOrFolder;
            if (is_dir($fileOrFolderFullPath)) {
                $dir = $fileOrFolderFullPath;
                $problem_files = array_merge($this->checkDirExcludedWords($dir, $wordArray), $problem_files);
            }
            if (is_file($fileOrFolderFullPath)) {
                $file = $fileOrFolderFullPath;
                $matchedWords = $this->checkFileExcludedWords($file, $wordArray);

                if ($matchedWords) {
                    $problem_files[$file] = $matchedWords;
                }
            }
        }

        return $problem_files;
    }

    private function checkFileExcludedWords($fileName, $wordArray): array
    {
        $matchedWords = [];

        $fileName = str_replace('////', '/', $fileName);
        if (filesize($fileName) === 0) {
            return $matchedWords;
        }

        $fileContent = file_get_contents($fileName);
        if (! $fileContent) {
            $msg = "Could not open ${fileName} to check for excluded words";

            GeneralMethods::output_to_screen($msg);
            UpdateModules::$unsolvedItems[$moduleObject->ModuleName] = $msg;
        }

        foreach ($wordArray as $word) {
            $matchCount = preg_match_all('/' . $word . '/i', $fileContent);

            if ($matchCount > 0) {
                array_push($matchedWords, $word);
            }
        }

        return $matchedWords;
    }
}
