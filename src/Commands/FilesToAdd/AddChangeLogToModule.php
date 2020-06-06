<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddChangeLogToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/CHANGELOG.md';

    protected $fileLocation = 'CHANGELOG.md';
}
