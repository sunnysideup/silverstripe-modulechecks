<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\ComposerJson;

use SilverStripe\Core\Config\Config;
use Sunnysideup\ModuleChecks\BaseCommands\UpdateComposer;

/**
 * sets the default installation folder
 */
class UpdateLicense extends UpdateComposer
{
    private static $license_type = 'BSD-3-Clause';

    public function run()
    {
        $json = $this->getJsonData();
        $json['license'] = Config::inst()->get(UpdateLicense::class, 'license_type');

        $this->setJsonData($json);
    }
}
