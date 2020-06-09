<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddHtAccessToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/.htaccess';

    protected $fileLocation = '.htaccess';

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
        return 'Add .htaccess file';
    }
}
