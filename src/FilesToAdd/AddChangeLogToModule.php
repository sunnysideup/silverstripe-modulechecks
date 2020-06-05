<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;


class AddChangeLogToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/CHANGELOG.md';

    protected $fileLocation = 'CHANGELOG.md';
}

