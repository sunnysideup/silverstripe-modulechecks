<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddContributingToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/CONTRIBUTING.md';

    protected $fileLocation = 'CONTRIBUTING.md';
}
