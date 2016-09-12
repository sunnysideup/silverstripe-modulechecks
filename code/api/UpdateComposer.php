<?php

abstract class UpdateComposer extends Object {

    public function UpdateComposer ($composerJsonObj) {
        $this->composerJsonObj = $composerJsonObj;
        if (! isset ($this->composerJsonObj->jsonData) ) {
            user_error ('No Json data!');
        }        
    }
        
    
    abstract public function run();
}
