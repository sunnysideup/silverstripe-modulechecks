<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddScrutinizerYmlToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.scrutinizer.yml';

    protected $fileLocation = '.scrutinizer.yml';
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
        return 'Add .scrutinizer.yml file';
    }
}
