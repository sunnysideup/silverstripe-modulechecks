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
        $files = ClassInfo::implementorsOf('AddFileToModule');
        $limitedFiles = $this->Config()->get('files_to_update');
        if($files && count($files)) {
            $modules = array_intersect($files, $limitedFileClasses);
        }
        $commands = ClassInfo::implementorsOf('RunCommandLineMethodOnModule');
        $limitedCommands = $this->Config()->get('commands_to_run');
        if($commands && count($commands)) {
            $commands = array_intersect($commands, $limitedCommands);
        }
        foreach($modules as $module) {
            
            $moduleIsPublishable = true;
            
            $moduleObject = GitHubModule::get_git_hub_module($module);
            
            /*print ("dsfdfs");
            $c = $this->Config()->get($module));
            var_dump ($c);
            die("=-=-=-");*/
            
            try {
                $moduleObject->cloneRepo();
            }
            catch (Exception $e) {
                print ("<li style=\"color:#f00;\"> Failed to clone $module - ". $e->getMessage() ."</li>");
                print ("<li> Moving to next module ...</li><br/>");
                continue;
            }
            /*foreach($files as $file) {
                //run file update
                $obj = $file::create($moduleObject->tempRootDir());
                $obj->run();
            }
            foreach($commands as $command) {
                //run file update
                $obj = $command::create($moduleObject->tempRootDir());
                $obj->run();
                //run command
            }*/
            
            
            
            if (!$this->checkReadMe($module)) {
                print ("<li style=\"color:#f00;\"> $module does not have a README.MD. </li>");
                $moduleIsPublishable = false;
            }
            
            if ($moduleIsPublishable) {
                $this->addCommitPush ($moduleObject);
            }
        }
        //to do ..
    }

    private function addCommitPush ($moduleObject) {
            $module = $moduleObject->ModuleName;
            try {
                $moduleObject->add();
            }
            catch (GitWrapper\GitException $e) {
                $errStr = $e->getMessage();
                if (stripos($errStr, 'did not match any files') === false) {
                    throw $e;
                }
                else {
                    print ("<li style=\"color:#00f;\">No new files to add to $module. </li>");                    
                }                
            }
            
            unset ($commitFail);
            unset ($nothingToCommit);
            try {
                $moduleObject->commit('Update Modules task');
            }
            catch (GitWrapper\GitException $e) {
                $errStr = $e->getMessage();
                
                $commitFail = true;
                if (stripos($errStr, 'nothing to commit') === false) {
                    throw $e;
                }
                else {
                    $nothingToCommit = true;
                    print ("<li style=\"color:#00f;\">No changes to commit for $module. </li>");                    
                }
            }

            unset ($pushFail);            
            if (!$commitFail) {

                try {
                    $moduleObject->push();
                }
                catch (Exception $e) {
                    $pushFail = true;
                    print ("<li style=\"color:#f00;\"> Failed to push module $module. </li>");
                    
                }
                
                if (!isset($pushfail) || (isset($pushFail) &&  !$pushFail)) {
                    $moduleObject->removeClone();
                }
            }
    }

    private function checkFile($module, $filename) {
        return file_exists($this->Config()->get('temp_folder').'/'.$module.'/'.$filename);
    }
    
    private function checkReadMe($module) {
        return $this->checkFile($module, "README.MD");
    }

}
