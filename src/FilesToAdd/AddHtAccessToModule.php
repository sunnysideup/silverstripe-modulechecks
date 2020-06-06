<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddHtAccessToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.htaccess';

    protected $fileLocation = '.htaccess';
}
