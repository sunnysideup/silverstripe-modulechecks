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
}
