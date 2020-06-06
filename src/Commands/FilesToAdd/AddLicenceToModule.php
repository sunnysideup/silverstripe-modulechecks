<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddLicenceToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/LICENSE';

    protected $fileLocation = 'LICENSE';
}
