<?php
/**
 * sets the default installation folder
 */
class CheckOrAddExtraArray extends UpdateComposer
{
    public function run()
    {
        $json = $this->getJsonData();

        if (isset($json['extra'])) {
            GeneralMethods::output_to_screen("<li> already has composer.json[extra][installer-name] </li>");

            return;
        } else {
            GeneralMethods::output_to_screen("<li> Adding 'extra' array to composer.json </li>");
            if (! isset($json['extra'])) {
                $json['extra'] = [];
            }
            $json['extra']['installer-name'] = str_replace('silverstripe-', '', $this->composerJsonObj->moduleName);
        }
        $this->setJsonData($json);
    }
}

