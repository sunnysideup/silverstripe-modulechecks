<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddLicenceToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/LICENSE';

    protected $fileLocation = 'LICENSE';

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Add LICENSE file';
    }
}
