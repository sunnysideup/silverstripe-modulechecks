<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddEditorConfigToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.editorconfig';

    protected $fileLocation = '.editorconfig';

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Add .editorconfig file';
    }
}
