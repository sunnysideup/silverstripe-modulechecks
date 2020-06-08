<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\Commands\OtherCommandsAbstract;
use Sunnysideup\ModuleChecks\Api\Scrutinizer;
use Sunnysideup\ModuleChecks\BaseObject;

class MoveOldReadMe extends ChecksAbstract
{


    protected function run() : bool
    {
        $automatedReadMe = $this->repo->Directory(). '/' . 'README.md';

        if (! file_exists($automatedReadMe)) {
            return false;
        }

        $oldreadmeDestinationFiles = [
            'docs/en/INDEX.md',
            'docs/en/README.old.md',
        ];

        $copied = false;
        foreach ($oldreadmeDestinationFiles as $file) {
            $filePath = $this->repo->Directory() . '/' . $file;

            if (! file_exists($filePath)) {
                FileSystem::makeFolder(dirname($filePath));
                GeneralMethods::output_to_screen('Copying ' . $automatedReadMe . ' to ' . $filePath);
                copy($automatedReadMe, $filePath);
                $copied = true;
            }
        }
        //delete the autoamted ReadMe
        if ($copied) {
            unlink($automatedReadMe);
        }

        return true;
    }
}
