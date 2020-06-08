<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddUserguideMdToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/docs/en/userguide.md';

    protected $fileLocation = 'docs/en/userguide.md';
}
