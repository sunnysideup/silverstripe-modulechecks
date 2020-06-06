<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddSourceReadmeToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/README.md';

    protected $fileLocation = 'README.md';
}
