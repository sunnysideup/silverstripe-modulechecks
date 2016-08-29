<?php

class ComposerRemoveHomePage extends UpdateComposer {

    public function run() {
        if (isset($this->composerJsonObj->jsonData->authors[0]->homepage)) {
            unset($this->composerJsonObj->jsonData->authors[0]->homepage);
        }
        if (isset($this->composerJsonObj->jsonData->homepage)) {
            unset($this->composerJsonObj->jsonData->homepage);
        }        
    }
}
