<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddContributingToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/CONTRIBUTING.md';

    protected $fileLocation = 'CONTRIBUTING.md';

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
        return 'Add CONTRIBUTING.md file';
    }
}
