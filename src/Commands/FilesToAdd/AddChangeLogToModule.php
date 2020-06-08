<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddChangeLogToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/CHANGELOG.md';

    protected $fileLocation = 'CHANGELOG.md';

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Add CHANGELOG.md file';
    }
}
