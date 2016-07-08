<?php
/**
 * adds or replaces a file
 * in a git hub module
 * in a module...
 *
 *
 */

abstract class AddFileToModule extends Object
{


    /**
     * root dir for module
     * e.g. /var/www/modules/mymodule
     * no final slash
     *
     * @var string
     */
    protected $rootDirForModule = '';


    /**
     * root dir for module
     * e.g.
     *  - README.md
     *  OR
     *  - docs/index.php
     *
     *
     * @var string
     */
    protected $fileLocation = '';

    /**
     * e.g.
     * http://www.mysite.com/myfile.txt
     * myfile.txt
     * where examples.txt will have the base dir + modulechecks director added to it
     * e.g.
     * examples/myfile.txt becomes
     * /var/www/myproject/modulechecks/examples/myfile.txt
     * @var string
     */
    protected $sourceLocation = '';

    function setRootDirForModule($rootDirForModule)
    {
        $this->$rootDirForModule = $rootDirForModule;
    }

    function setSourceLocation($sourceLocation)
    {
        $this->sourceLocation = $sourceLocation;
    }

    function setFileLocation($relativeDirAndFileName)
    {
        $this->fileLocation = $relativeDirAndFileName;
    }

    public function __construct($rootDirForModule = ''){
        $this->rootDirForModule = $rootDirForModule;
    }

    function run() {
        if( ! $this->rootDirForModule) {
            user_error('no root dir for module has been set');
        }
        if( ! $this->fileLocation) {
            user_error('File location not set');
        }
        $fileContent = $this->getStandardFile();
        $fileContent = $this->customiseStandardFile($fileContent);
        if($fileContent) {
            $this->saveFile($fileContent);
        }
    }

    /**
     * you can either return the string from the
     * `$sourceLocation` or you can just have a string here
     * that returns the data directly....
     *
     * @param string $fileContent
     *
     * @return bool - true on success, false on failure
     */
    protected function getStandardFile()
    {
        //$file = fopen ($rootDirForModule.'/'.$sourceLocation, "w");
        if ($file) {
            fwrite ($file, $fileContent);
            fclose ($file);
        }
        else {
            return false;
        }
    }

    /**
     * takes the standard file and adds any
     * customisations to it from the module
     *
     * @return bool - true on success, false on failure
     */
    protected function customiseStandardFile($fileContent)
    {
        $obj = $this->getCustomisationFile();
        $fileContent = $obj->customiseFile($this->fileLocation, $fileContent);
    }

    /**
     * writes the file
     *
     * @param string $fileContent
     *
     * @return bool - true on success, false on failure
     */
    protected function saveFile($fileContent) 
    {
        $file = fopen ($rootDirForModule.'/'.$fileLocation, "w");
        if ($file) {
            fwrite ($file, $fileContent);
            fclose ($file);
        }
        else {
            return false;
        }
    }
    /**
     *
     *
     * @return ModuleConfig (instance of ModuleConfigInterface)
     */
    protected function getCustomisationFile()
    {
        require_once(
            $this->rootDirForModule . '/ssmoduleconfigs/ModuleConfig.php'
        );
        return Injector::inst()->get('ModuleConfig');
    }


}
