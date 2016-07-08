<?php

/**
 * main class running all the updates
 *
 *
 */
class UpdateModules extends BuildTask
{


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
        $modules = GitRepoFinder::get_all_repos();
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
            $moduleObject = GitHubModule::get_git_hub_module($module);
            $moduleObject->clone();
            foreach($files as $file) {
                //run file update
                $obj = $file::create($moduleObject->tempRootDir())
                $obj->run();
            }
            foreach($commands as $command) {
                //run file update
                $obj = $command::create($moduleObject->tempRootDir())
                $obj->run();
                //run command
            }
            $moduleObject->add();
            $moduleObject->commit('adding stuff');
            $moduleObject->push();
            $moduleObject->removeClone();
        }
        //to do ..
    }

}
