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
            GeneralMethods::outputToScreen("<li> already has composer.json[extra][installer-name] </li>");

            return;
        } else {
            GeneralMethods::outputToScreen("<li> Adding 'extra' array to composer.json </li>");
            if (! isset($json['extra'])) {
                $json['extra'] = [];
            }
            $json['extra']['installer-name'] = str_replace('silverstripe-', '', $this->composerJsonObj->moduleName);
        }
        $this->setJsonData($json);
    }
}
