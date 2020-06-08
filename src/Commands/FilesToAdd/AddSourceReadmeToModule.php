<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddSourceReadmeToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/README.md';

    protected $fileLocation = 'README.md';
}
