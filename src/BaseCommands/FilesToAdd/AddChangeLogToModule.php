<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddChangeLogToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/CHANGELOG.md';

    protected $fileLocation = 'CHANGELOG.md';
}
