<?php

namespace Sunnysideup\ModuleChecks\Api;

use SilverStripe\Assets\Filesystem;

use Sunnysideup\ModuleChecks\BaseObject;

class FileMethods extends BaseObject
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
    /**
     * opens a location with curl to see if it exists.
     *
     * @param string $url
     *
     * @return boolean
     */
    public static function check_location_exists(string $url): bool
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


}
