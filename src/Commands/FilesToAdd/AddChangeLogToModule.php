<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddChangeLogToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/CHANGELOG.md';

    protected $fileLocation = 'CHANGELOG.md';

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Add CHANGELOG.md file';
    }
}
