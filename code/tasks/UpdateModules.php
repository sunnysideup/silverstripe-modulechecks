<?php

/**
 * main class running all the updates
 *
 *
 */
class UpdateModules extends BuildTask
{

    protected $title = "Update Modules";

    protected $description = "Adds files necessary for publishing a module to GitHub. The list of modules is specified in standard config or else it retrieves a list of modules from GitHub.";

    /**
     * e.g.
     * - moduleA
     * - moduleB
     * - moduleC
     *
     *
     * @var array
     */
    private static $modules_to_update = array();

    /**
     * e.g.
     * - ClassNameForUpdatingFileA
     * - ClassNameForUpdatingFileB
     *
     * @var array
     */
    private static $files_to_update = array();
    /**
     * e.g.
     * - ClassNameForUpdatingFileA
     * - ClassNameForUpdatingFileB
     *
     * @var array
     */
    private static $commands_to_run = array();

    public function run($request) {
        //$modules = GitRepoFinder::get_all_repos();
        $modules = array('silverstripe-phone_field');//hack to get around GitHub Api limit
        $limitedModules = $this->Config()->get('modules_to_update');
        if($limitedModules && count($limitedModules)) {
            $modules = array_intersect($modules, $limitedModules);
        }
        $files = ClassInfo::subclassesFor('AddFileToModule');
        array_shift($files);
        $limitedFileClasses = $this->Config()->get('files_to_update');
        if($limitedFileClasses && count($limitedFileClasses)) {
            $modules = array_intersect($files, $limitedFileClasses);
        }
        $commands = ClassInfo::subclassesFor('RunCommandLineMethodOnModule');
        array_shift($commands);
        $limitedCommands = $this->Config()->get('commands_to_run');
        if($limitedCommands && count($limitedCommands)) {
            $commands = array_intersect($commands, $limitedCommands);
        }
        foreach($modules as $count => $module) {

            echo "<h2>".($count+1).". ".$module."</h2>";

            $moduleObject = GitHubModule::get_or_create_github_module($module);
            $repository = $moduleObject->checkOrSetGitCommsWrapper($forceNew = true);
            foreach($files as $file) {
                //run file update
                $obj = $file::create($moduleObject->Directory());
                $obj->run();
            }
            foreach($commands as $command) {
                //run file update
                $obj = $command::create($moduleObject->Directory());
                $obj->run();
                //run command
            }
            if( ! $moduleObject->add()) { die("ERROR in add"); }
            if( ! $moduleObject->commit()) { die("ERROR in commit"); }
            if( ! $moduleObject->push()) { die("ERROR in push"); }
            if( ! $moduleObject->removeClone()) { die("ERROR in removeClone"); }
        }
        //to do ..
    }


    private function checkFile($module, $filename) {
        return file_exists($this->Config()->get('absolute_temp_folder').'/'.$module.'/'.$filename);
    }

    private function checkReadMe($module) {
        return $this->checkFile($module, "README.MD");
    }

}
