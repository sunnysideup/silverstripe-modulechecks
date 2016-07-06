<?php
/**
 * adds or replaces a file
 * in a module...
 *
 *
 */

class AddFileToModule extends Object {

    protected $fileLocation = '';

    protected $sourceLocation = '';

    function setSourceLocation($sourceLocation)
    {
        $this->sourceLocation = $$sourceLocation;
    }

    function setFileLocation($relativeDirAndFileName)
    {
        $this->fileLocation = $relativeDirAndFileName;
    }

    function run(){
        if( ! $this->sourceLocation) {
            user_error('Source location not set');
        }
        if( ! $this->fileLocation) {
            user_error('Source location not set');
        }
        $this->cloneRepo();
        $this->buildFile();
        $this->saveFile();
        $this->commitRepo();
        $this->pushRepo();
    }

    function cloneRepo()
    {

    }

    function getStandardFile()
    {

    }

    function customiseStandardFile()
    {

    }

    function saveFile()
    {

    }

    function >commitRepo(
    {

    }

    function >pushRepo()
    {

    }

}
