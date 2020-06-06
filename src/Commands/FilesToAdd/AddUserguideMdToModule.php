<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddUserguideMdToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/docs/en/userguide.md';

    protected $fileLocation = 'docs/en/userguide.md';
}
