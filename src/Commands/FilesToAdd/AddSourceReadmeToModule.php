<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddSourceReadmeToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/README.md';

    protected $fileLocation = 'README.md';
}
