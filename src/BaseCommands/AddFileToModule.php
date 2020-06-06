<?php

namespace Sunnysideup\ModuleChecks\BaseCommands\AddFileToModule;

use SilverStripe\Assets\Filesystem;
use SilverStripe\Control\Director;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\View\Requirements;
use SilverStripe\View\ViewableData;
use Sunnysideup\ModuleChecks\Model\GitHubModule;


abstract class AddFileToModule
{
    protected $gitReplaceArray = [
        '+++long-module-name-goes-here+++' => 'LongModuleName',
        '+++medium-module-name-goes-here+++' => 'MediumModuleName',
        '+++short-module-name-goes-here+++' => 'ShortModuleName',
        '+++module-name-goes-here+++' => 'ShortModuleName',
        '+++short-module-name-first-letter-capital+++' => 'ModuleNameFirstLetterCapital',
    ];

    protected $replaceArray = [
        '+++README_DOCUMENTATION+++' => 'Documentation',
        '+++README_SUGGESTED_MODULES+++' => 'SuggestedModules',
        '+++README_REQUIREMENTS+++' => 'Requirements',
        '+++README_INSTALLATION+++' => 'Installation',
        '+++README_AUTHOR+++' => 'Author',
        '+++README_ASSISTANCE+++' => 'Assistance',
        '+++README_CONTRIBUTING+++' => 'Contributing',
        '+++README_CONFIGURATION+++' => 'Configuration',
    ];

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

    protected $gitObject = null;

    public function __construct($gitObject)
    {
        parent::__construct();
        $this->gitObject = $gitObject;
        $rootDirForModule = $gitObject->Directory();
        $this->rootDirForModule = $rootDirForModule;
    }

    public function setRootDirForModule($rootDirForModule)
    {
        $this->{$rootDirForModule} = $rootDirForModule;
    }

    public function setSourceLocation($sourceLocation)
    {
        $this->sourceLocation = $sourceLocation;
    }

    public function setFileLocation($relativeDirAndFileName)
    {
        $this->fileLocation = $relativeDirAndFileName;
    }

    public function run()
    {
        if (! $this->rootDirForModule) {
            user_error('no root dir for module has been set');
        }
        if (! $this->fileLocation) {
            user_error('File location not set');
        }
        $fileContent = $this->getStandardFile();

        if ($this->useCustomisationFile) {
            $fileContent = $this->customiseStandardFile($fileContent);
        }

        $this->saveFile($fileContent);
        if ($fileContent) {
            $this->replaceWordsInFile();
        }
    }

    /**
     * @param string $file
     * @param GitHubModule $gitObject
     *
     * @return string
     */
    public function replaceWordsInFile()
    {
        foreach ($this->gitReplaceArray as $searchTerm => $replaceMethod) {
            $fileName = $this->rootDirForModule . '/' . $this->fileLocation;
            GeneralMethods::replaceInFile($fileName, $searchTerm, $this->gitObject->{$replaceMethod}());
        }

        foreach ($this->replaceArray as $searchTerm => $replaceMethod) {
            $fileName = $this->rootDirForModule . '/' . $this->fileLocation;
            GeneralMethods::replaceInFile($fileName, $searchTerm, $this->{$replaceMethod}());
        }
    }

    /**
     * @return string
     */
    public function getFileLocation()
    {
        return $this->fileLocation;
    }

    /**
     * @param string $text
     * @return string
     */
    public function replaceWordsInText($text)
    {
        $originalText = $text;
        foreach ($this->gitReplaceArray as $searchTerm => $replaceMethod) {
            $text = str_replace($searchTerm, $this->gitObject->{$replaceMethod}(), $text);
        }

        foreach ($this->replaceArray as $searchTerm => $replaceMethod) {
            $text = str_replace($searchTerm, $this->{$replaceMethod}(), $text);
        }
        return $text;
    }

    public function compareWithText($compareText)
    {
        $fileText = $this->getStandardFile();
        $text = $this->replaceWordsInText($fileText);
        return trim($text) === trim($compareText);
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
        } else {
            $fullFileName = Director::baseFolder() . '/' . $this->sourceLocation;
        }

        print "<li>${fullFileName}</li>";

        $file = fopen($fullFileName, 'r');
        if ($file) {
            $fileSize = filesize($fullFileName);

            if ($fileSize > 0) {
                $fileContents = fread($file, filesize($fullFileName));
            } else {
                $fileContents = '';
            }
            fclose($file);

            return $fileContents;
        }
        return false;
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
        GeneralMethods::output_to_screen('<li> Adding ' . $this->fileLocation . ' to module  </li>');

        /*
         * If fileLocation  contains folder, name then need to check
         * if folder exists
         */
        if (strpos($this->fileLocation, '/') !== false) {
            $folderPath = substr($this->fileLocation, 0, strrpos($this->fileLocation, '/'));

            //print_r ($this->rootDirForModule.'/'.$folderPath);

            if (! file_exists($this->rootDirForModule . '/' . $folderPath)) {
                $folder = Filesystem::makeFolder($this->rootDirForModule . '/' . $folderPath);
            }
        }

        if (isset($folderPath) && ! file_exists($this->rootDirForModule . '/' . $folderPath)) {
            user_error('could not find or create directory ' . $this->rootDirForModule . '/' . $folderPath);
        }

        $fileName = $this->rootDirForModule . '/' . $this->fileLocation;
        $this->fileLocation;

        $file = fopen($fileName, 'w');

        if ($file) {
            $result = fwrite($file, $fileContent);
            $a = file_exists($fileName);
        } else {
            return false;
        }
    }

    /**
     * @return ModuleConfig (instance of ModuleConfigInterface)
     */
    protected function getCustomisationFile()
    {
        require_once(
            $this->rootDirForModule . '/ssmoduleconfigs/ModuleConfig.php'
        );
        return Injector::inst()->get('ModuleConfig');
    }

    protected function getReadMeComponent($componentName)
    {
        $temp_dir = GitHubModule::Config()->get('absolute_temp_folder');
        $moduleName = $this->gitObject->ModuleName;

        $fileName = $temp_dir . '/' . $moduleName . '/docs/en/' . strtoupper($componentName) . '.md';

        set_error_handler([$this, 'catchFopenWarning'], E_WARNING);
        $file = fopen($fileName, 'r');
        restore_error_handler();

        if ($file) {
            $content = fread($file, filesize($filename));
        } else {
            $content = '';
        }
    }

    protected function Configuration()
    {
        return $this->getReadMeComponent('configuration');
    }

    protected function Contributing()
    {
        return $this->getReadMeComponent('contributing');
    }

    protected function Documentation()
    {
        return $this->getReadMeComponent('documentation');
    }

    protected function Requirements()
    {
        return $this->getReadMeComponent('requirements');
    }

    protected function Installation()
    {
        return $this->getReadMeComponent('installation');
    }

    protected function Author()
    {
        return $this->getReadMeComponent('author');
    }

    protected function Assistance()
    {
        return $this->getReadMeComponent('assistance');
    }

    protected function SuggestedModules()
    {
        return $this->getReadMeComponent('suggestedmodules');
    }

    private function catchFopenWarning($errno, $errstr)
    {
    }
}
