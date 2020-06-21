<?php

namespace Sunnysideup\ModuleChecks\Commands;

use SilverStripe\Assets\Filesystem;
use SilverStripe\Control\Director;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\View\Requirements;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Model\Module;
use Sunnysideup\Flush\FlushNow;

abstract class FilesToAddAbstract extends BaseCommand
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

    protected $repo = null;

    private static $enabled = false;

    public function __construct(?Module $repo = null)
    {
        parent::__construct($repo);
        if($this->repo) {
            $this->rootDirForModule = $this->repo->Directory();
        }
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

    abstract public function getDescription(): string;

    public function run()
    {
        if (! $this->rootDirForModule) {
            $this->logError('no root dir for module has been set');
        }
        if (! $this->fileLocation) {
            $this->logError('File location not set');
        }
        $fileContent = $this->getStandardFile();

        if ($this->useCustomisationFile) {
            $fileContent = $this->customiseStandardFile($fileContent);
        }

        $outcome = $this->saveFile($fileContent);
        if ($fileContent) {
            $this->replaceWordsInFile();
        }

        return $this->hasError($outcome);
    }

    /**
     * @param string $file
     * @param Module $repo
     *
     * @return string
     */
    public function replaceWordsInFile()
    {
        foreach ($this->gitReplaceArray as $searchTerm => $replaceMethod) {
            $fileName = $this->rootDirForModule . '/' . $this->fileLocation;
            $this->replaceInFile($fileName, $searchTerm, $this->repo->{$replaceMethod}());
        }

        foreach ($this->replaceArray as $searchTerm => $replaceMethod) {
            $fileName = $this->rootDirForModule . '/' . $this->fileLocation;
            $this->replaceInFile($fileName, $searchTerm, $this->{$replaceMethod}());
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
        foreach ($this->gitReplaceArray as $searchTerm => $replaceMethod) {
            $text = str_replace($searchTerm, $this->repo->{$replaceMethod}(), $text);
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

    public function getError(): string
    {
        return 'Could not add file.';
    }

    /*
     * Replaces all instances of a string in a file, and rewrites the file
     *
     * @param string $fileName
     * @param string $search
     * @param string $replacement
     *
     **/
    public function replaceInFile($fileName, $search, $replacement)
    {
        $file = fopen($fileName, 'r');
        if ($file) {
            $content = fread($file, filesize($fileName) * 2);
            $newContent = str_replace($search, $replacement, $content);
            fclose($file);

            $file = fopen($fileName, 'w');
            if ($file) {
                fwrite($file, $newContent);
                fclose($file);
            }
        }
    }

    /**
     * you can either return the string from the
     * `$sourceLocation` or you can just have a string here
     * that returns the data directly....
     *
     * @param string $fileContent
     *
     * @return string
     */
    protected function getStandardFile(): string
    {
        $isURL = (strpos($this->sourceLocation, '//') !== false);

        if ($isURL) {
            $fullFileName = $this->sourceLocation;
        } else {
            $fullFileName = Director::baseFolder() . '/' . $this->sourceLocation;
        }

        FlushNow::do_flush($fullFileName);

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
        return '';
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
        if ($obj) {
            $fileContent = $obj->customiseFile($this->fileLocation, $fileContent);
        }
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
        FlushNow::do_flush('Adding ' . $this->fileLocation . ' to module');

        /*
         * If fileLocation  contains folder, name then need to check
         * if folder exists
         */
        if (strpos($this->fileLocation, '/') !== false) {
            $folderPath = substr($this->fileLocation, 0, strrpos($this->fileLocation, '/'));

            //print_r ($this->rootDirForModule.'/'.$folderPath);

            if (! file_exists($this->rootDirForModule . '/' . $folderPath)) {
                Filesystem::makeFolder($this->rootDirForModule . '/' . $folderPath);
            }
        }

        if (isset($folderPath) && ! file_exists($this->rootDirForModule . '/' . $folderPath)) {
            $this->logError('could not find or create directory ' . $this->rootDirForModule . '/' . $folderPath);
        }

        $fileName = $this->rootDirForModule . '/' . $this->fileLocation;
        $this->fileLocation;

        $file = fopen($fileName, 'w');

        if ($file) {
            $result = fwrite($file, $fileContent);
            $exists = file_exists($fileName);
        } else {
            return false;
        }
        if (! $exists) {
            $this->logError('Tried to save file,  but can not find it: ' . $fileName);
        }
        if (! $result) {
            $this->logError('Tried to save file,  but can not write to it: ' . $fileName);
        }

        return $this->hasError();
    }

    /**
     * @return mixed
     */
    protected function getCustomisationFile()
    {
        $fileLocation = $this->rootDirForModule . '/ssmoduleconfigs/ModuleConfig.php';
        if (file_exists($fileLocation)) {
            require_once($fileLocation);
            return Injector::inst()->get('ModuleConfig');
        }
    }

    protected function getReadMeComponent($componentName): string
    {
        $temp_dir = BaseObject::absolute_path_to_temp_folder();
        $moduleName = $this->repo->ModuleName;

        $fileName = $temp_dir . '/' . $moduleName . '/docs/en/' . strtoupper($componentName) . '.md';

        set_error_handler([$this, 'catchFopenWarning'], E_WARNING);
        $file = fopen($fileName, 'r');
        restore_error_handler();

        if ($file) {
            $content = fread($file, filesize($fileName));
        } else {
            $content = '';
        }

        return $content;
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
