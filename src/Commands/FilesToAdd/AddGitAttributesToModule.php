<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddGitAttributesToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.gitattributes';

    protected $fileLocation = '.gitattributes';
}
