<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddHtAccessToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.htaccess';

    protected $fileLocation = '.htaccess';
}
