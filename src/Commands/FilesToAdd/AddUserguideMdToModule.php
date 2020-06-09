<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddUserguideMdToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/docs/en/userguide.md';

    protected $fileLocation = 'docs/en/userguide.md';

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
        return 'Add userguide.md file';
    }
}
