<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;



class AddEditorConfigToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/.editorconfig';

    protected $fileLocation = '.editorconfig';
}

