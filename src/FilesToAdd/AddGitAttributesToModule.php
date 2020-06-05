<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;

class AddGitAttributesToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.gitattributes';

    protected $fileLocation = '.gitattributes';
}
