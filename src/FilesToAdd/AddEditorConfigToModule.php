<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

class AddEditorConfigToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.editorconfig';

    protected $fileLocation = '.editorconfig';
}
