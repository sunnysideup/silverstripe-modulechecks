<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddTravisYmlToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.travis.yml';

    protected $fileLocation = '.travis.yml';
}
