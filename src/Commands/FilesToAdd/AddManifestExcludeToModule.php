<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddManifestExcludeToModule extends FilesToAddAbstract
{
    protected $sourceLocation = 'app/template_files/_module_data/_manifest_exclude';

    protected $fileLocation = '_module_data/_manifest_exclude';

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
        return 'Add _manifest_exclude file';
    }
}
