<?php

namespace Sunnysideup\ModuleChecks\Interfaces;

interface ModuleConfigInterface
{
    /**
     * array looks like this:
     *    array(
     *        "FileLocation" => "docs/myfile.txt,
     *        "SourceLocation => "http://...."
     *    );
     * @return array
     */
    public function params();

    /**
     * @param  [type] $location
     * @param  [type] $fileContent
     * @return [type]
     */
    public function customiseFile($location, $fileContent);
}
