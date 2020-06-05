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
     * @param  [type] $location    [description]
     * @param  [type] $fileContent [description]
     * @return [type]              [description]
     */
    public function customiseFile($location, $fileContent);
}
