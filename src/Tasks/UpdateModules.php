<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use Exception;
use SilverStripe\Assets\Filesystem;




use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use Sunnysideup\ModuleChecks\Api\ComposerJsonClass;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;
use Sunnysideup\ModuleChecks\Commands\ShellCommandsAbstract;
use Sunnysideup\ModuleChecks\Model\Module;

/**
 * main class running all the updates
 */
class UpdateModules extends BuildTask
{
    public static $unsolvedItems = [];

    protected $enabled = true;

    protected $title = 'Update Modules';

    protected $description = '
        Adds files necessary for publishing a module to GitHub.
        The list of modules is specified in standard config or else it retrieves a list of modules from GitHub.';

    /**
     * e.g.
     * - moduleA
     * - moduleB
     * - moduleC
     *
     * @var array
     */
    private static $modules_to_update = [];

    /**
     * e.g.
     * - ClassNameForUpdatingFileA
     * - ClassNameForUpdatingFileB
     *
     * @var array
     */
    private static $files_to_update = [];

    /**
     * e.g.
     * - ClassNameForUpdatingFileA
     * - ClassNameForUpdatingFileB
     *
     * @var array
     */
    private static $commands_to_run = [];

    public function run($request)
    {
        Environment::increaseTimeLimitTo(3600);

        //Check temp module folder is empty
        $tempFolder = Config::inst()->get(BaseObject::class, 'absolute_temp_folder');
        if (! file_exists($tempFolder)) {
            Filesystem::makeFolder($tempFolder);
        }
        $tempDirFiles = scandir($tempFolder);
        if (count($tempDirFiles) > 2) {
            die('<h2>' . $tempFolder . ' is not empty, please delete or move files </h2>');
        }

        //Get list of all modules from GitHub

        $modules = GitHubApi::get_all_repos();

        /*
         * Get files to add to modules
         * */
        $files = ClassInfo::subclassesFor(FilesToAddAbstract::class);
        array_shift($files);
        $limitedFileClasses = $this->Config()->get('files_to_update');
        if ($limitedFileClasses === []) {
            //do nothing
        } elseif ($limitedFileClasses === 'none') {
            $files = [];
        } elseif (is_array($limitedFileClasses) && count($limitedFileClasses)) {
            $files = array_intersect($files, $limitedFileClasses);
        }

        /*
         * Get commands to run on modules
         * */

        $commands = ClassInfo::subclassesFor(ShellCommandsAbstract::class);
        array_shift($commands);
        $limitedCommands = $this->Config()->get('commands_to_run');
        if ($limitedCommands === 'none') {
            $commands = [];
        } elseif (is_array($limitedCommands) && count($limitedCommands)) {
            $commands = array_intersect($commands, $limitedCommands);
        }

        set_error_handler('errorHandler', E_ALL);
        foreach ($modules as $count => $module) {
            $this->currentModule = $module;
            try {
                $this->processOneModule($module, $count, $files, $commands);
            } catch (Exception $e) {
                FlushNow::flushNow('Could not complete processing '.$module.': ' . $e->getMessage());
            }
        }

        restore_error_handler();

        $this->writeLog();
        //to do ..
    }

    public static function addUnsolvedProblem($moduleName, $problemString)
    {
        if (! isset(UpdateModules::$unsolvedItems[$moduleName])) {
            UpdateModules::$unsolvedItems[$moduleName] = [];
        }
        array_push(UpdateModules::$unsolvedItems[$moduleName], $problemString);
    }

    protected function errorHandler(int $errno, string $errstr)
    {
        FlushNow::flushNow('Could not complete processing module: ' . $errstr);

        UpdateModules::addUnsolvedProblem($this->currentModule, 'Could not complete processing module: ' . $errstr);

        return true;
    }

    protected function processOneModule($module, $count, $files, $commands)
    {
        if (stripos($module, 'silverstripe-') === false) {
            $module = 'silverstripe-' . $module;
        }
        echo '<h2>' . ($count + 1) . '. ' . $module . '</h2>';

        $moduleObject = Module::get_or_create_github_module($module);

        $this->checkUpdateTag($moduleObject);

        $updateComposerJson = $this->Config()->get('update_composer_json');

        // Check if all necessary files are perfect on GitHub repo already,
        // if so we can skip that module. But! ... if there are commands to run
        // over the files in the repo, then we need to clone the repo anyhow,
        // so skip the check
        if (count($commands) === 0 && ! $updateComposerJson) {
            // $moduleFilesOK = true;

            foreach ($files as $file) {
                $fileObj = $file::create($moduleObject);
                $checkFileName = $fileObj->getFileLocation();
                $GitHubFileText = $moduleObject -> getRawFileFromGithub($checkFileName);
                if ($GitHubFileText) {
                    $fileCheck = $fileObj->compareWithText($GitHubFileText);
                    if (! $fileCheck) {
                        // $moduleFilesOK = false;
                    }
                }
                // $moduleFilesOK = false;
            }
        }

        $moduleObject->checkOrSetGitCommsWrapper($forceNew = true);

        $this->moveOldReadMe($moduleObject);

        $checkConfigYML = $this->Config()->get('check_config_yml');
        if ($checkConfigYML) {
            $this->checkConfigYML($moduleObject);
        }

        if ($updateComposerJson) {
            $composerJsonObj = new ComposerJsonClass($moduleObject);
            $composerJsonObj->updateJsonFile();
            $moduleObject->setDescription($composerJsonObj->getDescription());
        }

        foreach ($files as $file) {
            //run file update

            $obj = $file::create($moduleObject);
            $obj->run();
        }

        $moduleDir = $moduleObject->Directory();

        foreach ($commands as $command) {
            //run file update

            $obj = $command::create($moduleDir);
            $obj->run();

            //run command
        }

        //Update Repository description
        //$moduleObject->updateGitHubInfo(array());

        if (! $moduleObject->add()) {
            $msg = 'Could not add files module to Repo';
            FlushNow::flushNow($msg);
            UpdateModules::$unsolvedItems[$moduleObject->ModuleName] = $msg;
            return;
        }
        if (! $moduleObject->commit()) {
            $msg = 'Could not commit files to Repo';
            FlushNow::flushNow($msg);
            UpdateModules::$unsolvedItems[$moduleObject->ModuleName] = $msg;
            return;
        }

        if (! $moduleObject->push()) {
            $msg = 'Could not push files to Repo';
            FlushNow::flushNow($msg);
            UpdateModules::$unsolvedItems[$moduleObject->ModuleName] = $msg;
            return;
        }
        if (! $moduleObject->removeClone()) {
            $msg = 'Could not remove local copy of repo';
            FlushNow::flushNow($msg);
            UpdateModules::$unsolvedItems[$moduleObject->ModuleName] = $msg;
        }

        $addRepoToScrutinzer = $this->Config()->get('add_to_scrutinizer');
        if ($addRepoToScrutinzer) {
            $moduleObject->addRepoToScrutinzer();
        }
    }

    protected function renameTest($moduleObject)
    {
        $oldName = $moduleObject->Directory() . '/tests/ChecksAbstract.php';

        if (! file_exists($oldName)) {
            print_r($oldName);
            return false;
        }

        $newName = $moduleObject->Directory() . 'tests/' . $moduleObject->ModuleName . 'Test.php';

        FlushNow::flushNow("Renaming ${oldName} to ${newName}");

        unlink($newName);

        rename($oldName, $newName);
    }

    protected function writeLog()
    {
        // $debug = $this->Config()->get('debug');

        $dateStr = date('Y/m/d H:i:s');

        $html = '<h1> Modules checker report at ' . $dateStr . '</h1>';

        if (count(UpdateModules::$unsolvedItems) === 0) {
            $html .= ' <h2> No unresolved problems in modules</h2>';
        } else {
            $html .= '
                <h2> Unresolved problems in modules</h2>

            <table border = 1>
                    <tr><th>Module</th><th>Problem</th></tr>';

            foreach (UpdateModules::$unsolvedItems as $moduleName => $problems) {
                if (is_array($problems)) {
                    foreach ($problems as $problem) {
                        $html .= '<tr><td>' . $moduleName . '</td><td>' . $problem . '</td></tr>';
                    }
                } elseif (is_string($problems)) {
                    $html .= '<tr><td>' . $moduleName . '</td><td>' . $problems . '</td></tr>';
                }
            }
            $html .= '</table>';
        }

        $logFolder = $this->Config()->get('logfolder');

        $filename = $logFolder . date('U') . '.html';

        FlushNow::flushNow("Writing to ${filename}");

        $result = file_put_contents($filename, $html);

        if (! $result) {
            FlushNow::flushNow('Could not write log file');
        }
    }
}
