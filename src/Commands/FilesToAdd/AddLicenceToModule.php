<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddLicenceToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/LICENSE';

    protected $fileLocation = 'LICENSE';
}
