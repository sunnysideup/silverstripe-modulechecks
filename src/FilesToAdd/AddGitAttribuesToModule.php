<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;

class AddGitAttribuesToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.gitattributes';

    protected $fileLocation = '.gitattributes';
}
