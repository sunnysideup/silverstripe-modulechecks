<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;


use Sunnysideup\ModuleChecks\Api\AddFileToModule;


class AddGitAttribuesToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.gitattributes';

    protected $fileLocation = '.gitattributes';
}
