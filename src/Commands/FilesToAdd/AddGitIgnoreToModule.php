<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddGitIgnoreToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.gitignore';

    protected $fileLocation = '.gitignore';
}
