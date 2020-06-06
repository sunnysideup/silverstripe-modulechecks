<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddEditorConfigToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.editorconfig';

    protected $fileLocation = '.editorconfig';
}
