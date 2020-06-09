<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddTravisYmlToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.travis.yml';

    protected $fileLocation = '.travis.yml';

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
        return 'Add .travis.yml file';
    }
}
