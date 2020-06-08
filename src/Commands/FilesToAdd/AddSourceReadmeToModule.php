<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddSourceReadmeToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/README.md';

    protected $fileLocation = 'README.md';

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Add README.md file';
    }

}
