<?php
/**
 * sets the default installation folder
 */
class UpdateLicense extends UpdateComposer
{
    private static $license_type = 'BSD-3-Clause';

    public function run()
    {
        $json = $this->getJsonData();
        $json['license'] = Config::inst()->get('UpdateLicense', 'license_type');

        $this->setJsonData($json);
    }
}

