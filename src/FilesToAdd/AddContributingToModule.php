<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddContributingToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/CONTRIBUTING.md';

    protected $fileLocation = 'CONTRIBUTING.md';
}
