<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;

class AddGitIgnoreToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.gitignore';

    protected $fileLocation = '.gitignore';
}
