<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddScrutinizerYmlToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.scrutinizer.yml';

    protected $fileLocation = '.scrutinizer.yml';
}
