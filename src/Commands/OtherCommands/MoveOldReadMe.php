<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class MoveOldReadMe extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function getDescription(): string
    {
        return 'Move Read Me to right place';
    }

    public function run(): bool
    {
        $automatedReadMe = $this->repo->Directory() . '/' . 'README.md';

        if (! file_exists($automatedReadMe)) {
            $this->logError('Could not find ' . $this->repo->Directory() . '/' . 'README.md');
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
                FlushNow::flushNow('Copying ' . $automatedReadMe . ' to ' . $filePath);
                copy($automatedReadMe, $filePath);
                $copied = true;
            }
        }
        //delete the autoamted ReadMe
        if ($copied) {
            unlink($automatedReadMe);
        }

        return $this->hasError();
    }
}
