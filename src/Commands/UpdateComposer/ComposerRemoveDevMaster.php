<?php

class ComposerRemoveDevMaster extends UpdateComposer
{
    public function run()
    {
        $json = $this->composerJsonObj->getJsonData();
        $require = $json['require'];
        foreach ($require as $requirement => $value) {
            $isDevMaster = preg_match('/dev.?master/', strtolower($value)) === 1;
            if ($isDevMaster) {
                $json['require'][$requirement] = '*';
            }
        }
        return $this->composerJsonObj->setJsonData($json);
    }
}
