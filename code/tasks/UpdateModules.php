<?php

/**
 * main class running all the updates
 *
 *
 */
class UpdateModules extends BuildTask
{
    protected $enabled = true;

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

    public function run($request)
    {
        increase_time_limit_to(3600);

        //Check temp module folder is empty
        $tempFolder = GitHubModule::Config()->get('absolute_temp_folder');
        $tempDirFiles = scandir($tempFolder);
        if (count($tempDirFiles) > 2) {
            die('<h2>' . $tempFolder . ' is not empty, please delete or move files </h2>');
        }

        //Get list of all modules from GitHub
        $gitUserName = $this->Config()->get('git_user_name');
        
        $modules = GitHubModule::getRepoList();
        


        $updateComposerJson = $this->Config()->get('update_composer_json');
        

		
        $limitedModules = $this->Config()->get('modules_to_update');

        if ($limitedModules && count($limitedModules)) {
            $modules = array_intersect($modules, $limitedModules);
        }



        /*
         * Get files to add to modules
         * */
        $files = ClassInfo::subclassesFor('AddFileToModule');

        array_shift($files);
        $limitedFileClasses = $this->Config()->get('files_to_update');
        if ($limitedFileClasses && count($limitedFileClasses)) {
            $files = array_intersect($files, $limitedFileClasses);
        }

        /*
         * Get commands to run on modules
         * */

        $commands = ClassInfo::subclassesFor('RunCommandLineMethodOnModule');


        array_shift($commands);
        $limitedCommands = $this->Config()->get('commands_to_run');
        if ($limitedCommands && count($limitedCommands)) {
            $commands = array_intersect($commands, $limitedCommands);
        }


        foreach ($modules as $count => $module) {
            if (stripos($module, 'silverstripe-')  === false) {
                $module = "silverstripe-" . $module;
            }
            echo "<h2>" . ($count+1) . ". ".$module."</h2>";


            $moduleObject = GitHubModule::get_or_create_github_module($module);

            $this->checkUpdateTag($moduleObject);

            // Check if all necessary files are perfect on GitHub repo already,
            // if so we can skip that module. But! ... if there are commands to run
            // over the files in the repo, then we need to clone the repo anyhow,
            // so skip the check
            if (count($commands) == 0 && ! $updateComposerJson) {
                $moduleFilesOK = true;

                foreach ($files as $file) {
                    $fileObj = $file::create($moduleObject);
                    $checkFileName = $fileObj->getFileLocation();
                    $GitHubFileText = $moduleObject -> getRawFileFromGithub($checkFileName);
                    if ($GitHubFileText) {
                        $fileCheck = $fileObj->compareWithText($GitHubFileText);
                        if (! $fileCheck) {
                            $moduleFilesOK = false;
                        }
                    } else {
                        $moduleFilesOK = false;
                    }
                }

                if ($moduleFilesOK) {
                    GeneralMethods::outputToScreen("<li> All files in $module OK, skipping to next module ... </li>");
                    continue;
                }
            }

            $repository = $moduleObject->checkOrSetGitCommsWrapper($forceNew = true);


			$this->moveOldReadMe($moduleObject);

            $this->checkConfigYML($moduleObject);


            if ($updateComposerJson) {
                $composerJsonObj = new ComposerJson($moduleObject);
                $composerJsonObj->updateJsonData();
                $moduleObject->setDescription($composerJsonObj->getDescription());
            }

<<<<<<< HEAD
			$excludedWords = $this->Config()->get('excluded_words');
			
			
			if (count($excludedWords) > 0) 
			{
				$folder = GitHubModule::Config()->get('absolute_temp_folder') . '/' . $moduleObject->moduleName . '/';

				$results = $this->checkDirExcludedWords($folder.'/'.$moduleObject->modulename, $excludedWords);
				
				
				if ($results && count ($results > 0)) 
				{
					$msg = "<h4>the following excluded words were found: </h4>";
					foreach ($results as $file => $words) {
						foreach ($words as $word) {
							$msg .= "<li>$word in $file</li>";
						}
					}
					
					
					trigger_error ("excluded words found in files(s)");
					GeneralMethods::outputToScreen ($msg);
				}
				
			}


            foreach($files as $file) {
=======
            foreach ($files as $file) {
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
                //run file update

                $obj = $file::create($moduleObject);
                $obj->run();
            }

            foreach ($commands as $command) {
                //run file update
                $obj = $command::create($moduleObject->Directory());
                $obj->run();
                //run command
            }

            //Update Repository description
            //$moduleObject->updateGitHubInfo(array());

            if (! $moduleObject->add()) {
                die("ERROR in add");
            }
            if (! $moduleObject->commit()) {
                die("ERROR in commit");
            }
            if (! $moduleObject->push()) {
                die("ERROR in push");
            }
            if (! $moduleObject->removeClone()) {
                die("ERROR in removeClone");
            }

            $moduleObject->addRepoToScrutinzer();
        }
        //to do ..
    }

    protected function checkConfigYML($module)
    {
<<<<<<< HEAD
        $configYml = ConfigYML::create($module)->reWrite();
 
=======
        $configYml = ConfigYML::create($module);
        $loadedYml =$configYml->readYMLFromFile();

        if ($loadedYml) {
            print_r($this->yaml_data);
            die();
        } else {
            die("sdfasdfasd");
        }
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
    }

    private function checkFile($module, $filename)
    {
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        return file_exists($folder.'/'.$module.'/'.$filename);
    }

    private function checkReadMe($module)
    {
        return $this->checkFile($module, "README.MD");
    }

    private function checkDirExcludedWords($directory, $wordArray)
    {
        $filesAndFolders = scandir($directory);

        $problem_files = array();
        foreach ($filesAndFolders as $fileOrFolder) {
<<<<<<< HEAD

            if ($fileOrFolder == '.' || $fileOrFolder == '..' || $fileOrFolder == '.git'  ) {
=======
            if ($fileOrFolder == '.' or $fileOrFolder == '..') {
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
                continue;
            }

            $fileOrFolderFullPath = $directory . '/' . $fileOrFolder;
            if (is_dir($fileOrFolderFullPath)) {
                $dir = $fileOrFolderFullPath;
<<<<<<< HEAD
                $problem_files = array_merge ($this->checkDirExcludedWords ($dir, $wordArray) , $problem_files);
=======
                $this->checkDirExcludedWords($dir, $wordArray);
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
            }
            if (is_file($fileOrFolderFullPath)) {
                $file = $fileOrFolderFullPath;
                $matchedWords = $this->checkFileExcludedWords($file, $wordArray);

                if ($matchedWords) {
<<<<<<< HEAD
		
                   $problem_files[$file] = $matchedWords;
=======
                    $problem_files[$file] = $matchedWords;
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
                }
            }
        }

        return $problem_files;
    }

<<<<<<< HEAD
    private function checkFileExcludedWords($fileName, $wordArray) {
		

        $matchedWords = array();

		$fileName = str_replace ('////', '/',  $fileName);
		if (filesize ($fileName) == 0 ) return $matchedWords; 

=======
    private function checkFileExcludedWords($fileName, $wordArray)
    {
        $file = fopen($fileName, 'r');

        $matchedWords = array();

        if (! $file) {
            return false;
        }
        $fileContent = fread($file, filesize($fileName));
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4

        $fileContent = file_get_contents($fileName);
		if (!$fileContent) (die ("could not open $fileName</br>"));

<<<<<<< HEAD
        foreach ($wordArray as $word)  {
			

=======
        foreach ($wordArray as $word) {
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
            $matches = array();
            $matchCount = preg_match_all('/' . $word . '/i', $fileContent);
            
            
           
            
            
            if ($matchCount > 0) {
                array_push ($matchedWords, $word);

            }
        }

        return $matchedWords;
    }

    private function checkUpdateTag($moduleObject)
    {
        $aWeekAgo = strtotime("-1 weeks");
        $tag = $moduleObject->getLatestTag();

        $commitTime = $moduleObject->getLatestCommitTime();

        if (! $commitTime) { // if no commits, cannot create a tag
            return false;
        }

        $createTag = false;

        if (! $tag) {
            $createTag = true;
            $newTagString = '1.0.0';
        } elseif ($tag && $commitTime > $tag['timestamp'] && $commitTime < $aWeekAgo) {
            $createTag = true;
            $tag['tagparts'][1] = $tag['tagparts'][1] + 1;
            $newTagString = trim(implode('.', $tag['tagparts']));
        }

        if ($createTag) {
            GeneralMethods::outputToScreen('<li> Creating new tag  '.$newTagString.' ... </li>');

            //git tag -a 0.0.1 -m "testing tag"
            $options = array(
                'a' => $newTagString,
                'm' => $this->Config()->get('tag_create_message')
            );

            $moduleObject->createTag($options);
        }

        return true;
    }
<<<<<<< HEAD
    
    protected function moveOldReadMe($moduleObject) {
		$tempDir = GitHubModule::Config()->get('absolute_temp_folder');
		$oldReadMe = $tempDir . '/' .  $moduleObject->ModuleName . '/' .'README.md';
		
		if (!file_exists($oldReadMe))
		{
			return false;
		}
		

		$oldreadmeDestinationFiles = array (
				'docs/en/INDEX.md',
				'docs/en/README.old.md', 
			);


		$copied = false;
		foreach ($oldreadmeDestinationFiles as $file) {
			$filePath = $tempDir . '/' .  $moduleObject->ModuleName . '/' . $file;
			
			if (!file_exists($filePath)) {
				$copied = true;
				copy($oldReadMe, $filePath);
			}
			
		}
		if ($copied) 
		{
			unlink ($oldReadMe);
		}
	}


=======
>>>>>>> 18c1226d4f2d331251e5a396c55caff08c4b55c4
}
