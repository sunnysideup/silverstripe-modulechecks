<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddContributingToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/CONTRIBUTING.md';

    protected $fileLocation = 'CONTRIBUTING.md';
}
