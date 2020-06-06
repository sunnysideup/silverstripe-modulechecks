<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddLicenceToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/LICENSE';

    protected $fileLocation = 'LICENSE';
}
