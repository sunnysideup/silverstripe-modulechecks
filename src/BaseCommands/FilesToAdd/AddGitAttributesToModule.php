<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddGitAttributesToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.gitattributes';

    protected $fileLocation = '.gitattributes';
}
