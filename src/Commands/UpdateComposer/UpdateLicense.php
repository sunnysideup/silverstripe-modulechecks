<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use SilverStripe\Core\Config\Config;
use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

/**
 * sets the default installation folder
 */
class UpdateLicense extends UpdateComposerAbstract
{
    public function run()
    {
        $json = $this->getJsonData();
        $json['license'] = Config::inst()->get(UpdateLicense::class, 'license_type');

        $this->setJsonData($json);
    }

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
        return 'Update license type.';
    }
}
