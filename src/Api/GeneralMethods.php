<?php

namespace Sunnysideup\ModuleChecks\Api;

use SilverStripe\Assets\Filesystem;

use SilverStripe\Control\Director;
use SilverStripe\ORM\DB;
use Sunnysideup\ModuleChecks\BaseObject;

class GeneralMethods extends BaseObject
{
    /**
     * opens a location with curl to see if it exists.
     *
     * @param string $url
     *
     * @return boolean
     */
    public static function check_location(string $url): bool
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, true);
        curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $outcome = $httpCode === intval(200) ? true : false;
        curl_close($handle);

        return $outcome;
    }

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
