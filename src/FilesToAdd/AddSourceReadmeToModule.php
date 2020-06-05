<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;


class AddSourceReadmeToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/README.md';

    protected $fileLocation = 'README.md';
}

