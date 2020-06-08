<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddChangeLogToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/CHANGELOG.md';

    protected $fileLocation = 'CHANGELOG.md';
}
