<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddGitIgnoreToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.gitignore';

    protected $fileLocation = '.gitignore';
}
