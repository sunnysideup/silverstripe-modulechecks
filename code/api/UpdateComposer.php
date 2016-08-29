<?php

class UpdateComposer extends Object {

    public function UpdateComposer ($composerJsonObj) {
        $this->composerJsonObj = $composerJsonObj;
    }
        
    
    abstract public function run();
}
