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

    protected $replaceArray = array(
        '+++long-module-name-goes-here+++' => 'LongModuleName',
        '+++medium-module-name-goes-here+++' => 'MediumModuleName',
        '+++short-module-name-goes-here+++' => 'ShortModuleName'
    );

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
    protected $sourceLocation = 'please set in files that extend ';

    protected $useCustomisationFile = false;

    protected $gitObject=null;

    function __construct($gitObject)
    {
        parent::__construct();
        $this->gitObject = $gitObject;
        $rootDirForModule = $gitObject->Directory();
        $this->rootDirForModule = $rootDirForModule;
    }

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


    function run() {
        if( ! $this->rootDirForModule) {
            user_error('no root dir for module has been set');
        }
        if( ! $this->fileLocation) {
            user_error('File location not set');
        }
        $fileContent = $this->getStandardFile();

        if ($this->useCustomisationFile) {
            $fileContent = $this->customiseStandardFile($fileContent);
        }
        if($fileContent) {
            $this->saveFile($fileContent);
            $this->replaceWordsInFile();
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
        $isURL = (strpos($this->sourceLocation, '//') !== false);

        if ($isURL) {
            $fullFileName = $this->sourceLocation;
        }
        else {
            $fullFileName = Director::baseFolder().'/modulechecks/'.$this->sourceLocation;
        }


        $file = fopen ($fullFileName, "r");
        if ($file) {
            $fileContents = fread ($file, filesize($fullFileName));
            fclose ($file);

            return $fileContents;
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
        $fileName = $this->rootDirForModule.'/'.$this->fileLocation;



        $file = fopen ($fileName, "w");

        if ($file) {
            $result = fwrite ($file, $fileContent);
            $a = file_exists ($fileName);
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

    /**
     * @param string $file
     * @param GitHubModule $gitObject
     *
     * @return string
     */ 
    public function replaceWordsInFile() 
    {
        foreach($this->replaceArray as $searchTerm => $replaceMethod) {

            $fileName = $this->rootDirForModule.'/'.$this->fileLocation;
            GeneralMethods::replaceInFile($fileName, $searchTerm, $this->gitObject->$replaceMethod());
        }
        
    }    

}
