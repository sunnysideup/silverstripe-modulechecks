<?php
/**
 * sets the default installation folder
 */
class UpdateLicense extends UpdateComposer {


    public function run() {
        $json = $this->getJsonData();
        $json['license'] = 'BSD-3-Clause';

        $this->setJsonData($json);
    }
}
