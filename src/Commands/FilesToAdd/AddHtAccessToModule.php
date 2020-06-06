<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddHtAccessToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.htaccess';

    protected $fileLocation = '.htaccess';
}
