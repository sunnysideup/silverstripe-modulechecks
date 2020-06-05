<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;


use Sunnysideup\ModuleChecks\Api\AddFileToModule;


class AddTravisYmlToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.travis.yml';

    protected $fileLocation = '.travis.yml';
}
