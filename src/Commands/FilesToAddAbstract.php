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

    public function __construct(Module $repo)
    {
        parent::__construct($repo);
        $this->rootDirForModule = $this->repo->Directory();
    }

    public function setRootDirForModule(string $rootDirForModule)
    {
        $this->rootDirForModule = $rootDirForModule;
        return $this;
    }

    public function setSourceLocation(string $sourceLocation)
    {
        $this->sourceLocation = $sourceLocation;
        return $this;
    }

    public function setFileLocation($relativeDirAndFileName)
    {
        $this->fileLocation = $relativeDirAndFileName;
    }

    abstract public function getDescription(): string;

    public function run() : bool
    {
        if (! $this->rootDirForModule) {
            $this->logError('no root dir for module has been set');
            return true;
        }
        if (! $this->fileLocation) {
            $this->logError('File location not set');
            return true;
        }
        $fileContent = $this->getStandardFile();

        if ($this->useCustomisationFile) {
            $fileContent = $this->customiseStandardFile($fileContent);
        }

        $outcome = $this->saveFile($fileContent);
        if ($fileContent) {
            $this->replaceWordsInFile();
        }
        if( ! $outcome) {
            $this->logError('Could not save file: ' . $this->fileLocation);
        }
        return $this->hasError();
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
            $this->replaceInFile($this->getFilePath(), $searchTerm, $this->repo->{$replaceMethod}());
        }

        foreach ($this->replaceArray as $searchTerm => $replaceMethod) {
            $this->replaceInFile($this->getFilePath(), $searchTerm, $this->{$replaceMethod}());
        }
    }

    protected function getFilePath() : string
    {
        return $this->rootDirForModule . '/' . $this->fileLocation;
    }

    protected function checkFolderPath() : bool
    {
        $fullPath = dirname($this->getFilePath());
        if (! file_exists($fullPath)) {
            Filesystem::makeFolder($fullPath);
        }

        if (! isset($fullPath) || ! file_exists($fullPath)) {
            $this->logError('could not find or create directory ' . $fullPath);
            return false;
        }

        return true;
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
    protected function saveFile($fileContent) : bool
    {
        FlushNow::do_flush('Adding ' . $this->fileLocation . ' to module');

        $this->checkFolderPath();

        $fileName = $this->getFilePath();

        $file = fopen($fileName, 'w');

        if ($file) {
            $result = fwrite($file, $fileContent);
            $exists = file_exists($fileName);
        } else {
            $this->logError('Could not open: ' . $fileName);
            return false;
        }
        if (! $exists) {
            $this->logError('Tried to save file,  but can not find it: ' . $fileName);
            return false;
        }
        if (! $result) {
            $this->logError('Tried to save file,  but can not write to it: ' . $fileName);
            return false;
        }

        return true;
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
        if(file_exists($fileName)) {
            return file_get_contents($fileName);
        }

        return '';
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


}
