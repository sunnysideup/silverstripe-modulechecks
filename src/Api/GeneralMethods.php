<?php

namespace Sunnysideup\ModuleChecks\Api;

use SilverStripe\Assets\Filesystem;

use Sunnysideup\ModuleChecks\BaseObject;

class GeneralMethods extends BaseObject
{


    /*
     * Recursively removes a directory
     *
     * @param  string $path
     */

    public static function removeDirectory(string $path)
    {
        Filesystem::removeFolder($path);
    }

    /*
     * Replaces all instances of a string in a file, and rewrites the file
     *
     * @param string $fileName
     * @param string $search
     * @param string $replacement
     *
     **/
    public static function replaceInFile($fileName, $search, $replacement)
    {
        $file = fopen($fileName, 'r');
        if ($file) {
            $content = fread($file, filesize($fileName) * 2);
            $newContent = str_replace($search, $replacement, $content);
            fclose($file);

            $file = fopen($fileName, 'w');
            if ($file) {
                fwrite($file, $newContent);
                fclose($file);
            }
        }
    }
}
