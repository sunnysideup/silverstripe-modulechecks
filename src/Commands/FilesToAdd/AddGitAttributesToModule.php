<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddGitAttributesToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.gitattributes';

    protected $fileLocation = '.gitattributes';

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
        return 'Add .gitattributes file';
    }
}
