<?php

class ComposerRemoveHomePage extends UpdateComposer
{
    public function run()
    {
        $json = $this->composerJsonObj->getJsonData();
        if (isset($json['authors'][0]['homepage'])) {
            unset($json['authors'][0]['homepage']);
        }
        if (isset($json['homepage'])) {
            unset($json['homepage']);
        }

        return $this->composerJsonObj->setJsonData($json);
    }
}
