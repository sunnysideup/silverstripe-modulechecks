<?php

namespace Sunnysideup\ModuleChecks\Api;



use FileSystem;

use SilverStripe\Control\Director;
use SilverStripe\ORM\DB;
use SilverStripe\View\ViewableData;


/**
 * ### @@@@ START REPLACEMENT @@@@ ###
 * WHY: automated upgrade
 * OLD:  extends Object (ignore case)
 * NEW:  extends ViewableData (COMPLEX)
 * EXP: This used to extend Object, but object does not exist anymore. You can also manually add use Extensible, use Injectable, and use Configurable
 * ### @@@@ STOP REPLACEMENT @@@@ ###
 */
class GeneralMethods extends ViewableData
{
    /**
     * opens a location with curl to see if it exists.
     *
     * @param string $url
     *
     * @return boolean
     */
    public static function check_location($url)
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, true);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $outcome = $httpCode === 200;
        curl_close($handle);
        return $outcome;
    }

    /**
     * @use
     * ```
     *    GeneralMethods::output_to_screen('asdf', 'created')
     * ```
     * @see DB::alteration_message for types...
     *
     * @param  string $message
     * @param  string $type
     */
    public static function output_to_screen($message, $type = '')
    {
        if (Director::is_cli()) {
            DB::alteration_message($message, $type);
        } else {
            echo '<br />';
            flush();
            ob_end_flush();
            DB::alteration_message($message, $type);
            ob_start();
        }
    }

    /*
     * Recursively removes a directory
     *
     * @param  string $path
     */

    public static function removeDirectory($path)
    {
        FileSystem::removeFolder($path);
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
