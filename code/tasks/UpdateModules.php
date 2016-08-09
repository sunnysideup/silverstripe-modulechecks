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
        // $modules = GitRepoFinder::get_all_repos();
        $modules = array('cms_tricks_for_apps',
                'cms_edit_link_field',
                'frontendeditor',
                'payment_stripe',
                'table_filter_sort',
                'us_phone_number',
                'blog_shared_categorisation',
                'comments_add_recaptcha',
                'ecommerce_cloud_flare_geoip',
                'ecommerce_nutritional_products',
                'ecommerce_stockists',
                'email_address_database_field',
                'import_task',
                'pdf_upload_field',
                'perfect_cms_images',
                'phone_field',
                'share_this_simple',
                'webpack_requirements_backend');//hack to get around GitHub Api limit
                    
        $limitedModules = $this->Config()->get('modules_to_update');


        if($limitedModules && count($limitedModules)) {
            $modules = array_intersect($modules, $limitedModules);
        }

        
        /*
         * Get files to add to modules
         * */
        $files = ClassInfo::subclassesFor('AddFileToModule');

        array_shift($files);
        $limitedFileClasses = $this->Config()->get('files_to_update');
        if($limitedFileClasses && count($limitedFileClasses)) {
            $files = array_intersect($files, $limitedFileClasses);
        }
        
        /*
         * Get commands to run on modules
         * */
         
        $commands = ClassInfo::subclassesFor('RunCommandLineMethodOnModule');
        array_shift($commands);
        $limitedCommands = $this->Config()->get('commands_to_run');
        if($limitedCommands && count($limitedCommands)) {
            $commands = array_intersect($commands, $limitedCommands);
        }
        foreach($modules as $count => $module) {

            if ( stripos($module, 'silverstripe-')  === false ) {
                $module = "silverstripe-" . $module;
            }
            echo "<h2>".($count+1) . ". ".$module."</h2>";

            
            $moduleObject = GitHubModule::get_or_create_github_module($module);
            $repository = $moduleObject->checkOrSetGitCommsWrapper($forceNew = true);
            foreach($files as $file) {
                //run file update
                $obj = $file::create($moduleObject);
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

    private function checkDirExcludedWords($directory, $wordArray) {
        $filesAndFolders = scandir ($directory);
        
        $problem_files = array();
        foreach ($filesAndFolders as $fileOrFolder) {
            
            if ($fileOrFolder == '.' or $fileOrFolder == '..') {
                continue;
            }
            
            $fileOrFolderFullPath = $directory . '/' . $fileOrFolder;
            if (is_dir($fileOrFolderFullPath)) {
                $dir = $fileOrFolderFullPath;
                $this->checkDirExcludedWords ($dir, $wordArray);
            }
            if (is_file($fileOrFolderFullPath)) {
                $file = $fileOrFolderFullPath;
                $matchedWords = $this->checkFileExcludedWords($file, $wordArray);
                
                if ($matchedWords) {
                   $problem_files[$file] = $matchedWords;
                }
            }
        }
        return $problem_files;
    }

    private function checkFileExcludedWords($fileName, $wordArray) {
        $file = fopen($fileName, 'r');

        $matchedWords = array();
        
        if (! $file) return false;
        $fileContent = fread($file, filesize($fileName));

        
        foreach ($wordArray as $word)  {
            $matches = array();
            $matchCount = preg_match_all('/' . $word . '/i', $fileContent);
            if ($matchCount > 0) {
                $matchedWords[] = $word;
            }
        }

        fclose($file);
        return $matchedWords;
        
    }


}
