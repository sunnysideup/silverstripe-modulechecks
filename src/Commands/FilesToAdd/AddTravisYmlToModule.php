<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddTravisYmlToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.travis.yml';

    protected $fileLocation = '.travis.yml';
}
