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
    
    public static $unsolvedItems = array();

    public function run($request) {
        increase_time_limit_to(3600);

        //Check temp module folder is empty
        $tempFolder = GitHubModule::Config()->get('absolute_temp_folder');
        $tempDirFiles = scandir($tempFolder);
        if (count($tempDirFiles) > 2) {
            die ( '<h2>' . $tempFolder . ' is not empty, please delete or move files </h2>');
        }

        //Get list of all modules from GitHub
        $gitUserName = $this->Config()->get('git_user_name');
		
		$modules = GitHubModule::getRepoList();
		


        $updateComposerJson = $this->Config()->get('update_composer_json');
        

		
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


		set_error_handler ('errorHandler', E_ALL);
        foreach($modules as $count => $module) {
			$this->currentModule = $module;
			try {
				
				$this->processOneModule($module, $count, $files, $commands, $updateComposerJson);
			}
			catch (Exception $e) {
				GeneralMethods::outputToScreen ("<li> Could not complete processing $module: " .  $e->getMessage() . " </li>");
			}
			

        }
 
		restore_error_handler();
        
        $this->writeLog();
        //to do ..
    }
    
    protected function errorHandler(int $errno , string $errstr) {

		GeneralMethods::outputToScreen ("<li> Could not complete processing module: " .  $errstr . " </li>");
  
        UpdateModules::addUnsolvedProblem($this->currentModule, "Could not complete processing module: " . $errstr);
              
		return true;
	}
    
    protected function processOneModule($module, $count, $files, $commands, $updateComposerJson) {
		    
		    if ( stripos($module, 'silverstripe-')  === false ) {
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

                foreach($files as $file) {
                    $fileObj = $file::create($moduleObject);
                    $checkFileName = $fileObj->getFileLocation();
                    $GitHubFileText = $moduleObject -> getRawFileFromGithub($checkFileName);
                    if ($GitHubFileText) {
                        $fileCheck = $fileObj->compareWithText($GitHubFileText);
                        if ( ! $fileCheck) {
                            $moduleFilesOK = false;
                        }
                    }
                    else {
                        $moduleFilesOK = false;
                    }
                }
                
                

                if ($moduleFilesOK) {
                    GeneralMethods::outputToScreen ("<li> All files in $module OK, skipping to next module ... </li>");
                    continue;
                }
            }

            $repository = $moduleObject->checkOrSetGitCommsWrapper($forceNew = true);


			$this->moveOldReadMe($moduleObject);

            $this->checkConfigYML($moduleObject);


            if ($updateComposerJson) {
                $composerJsonObj = new ComposerJson ($moduleObject);
                $composerJsonObj->updateJsonData();
                $moduleObject->setDescription($composerJsonObj->getDescription());
            }

			$excludedWords = $this->Config()->get('excluded_words');
			
			
			if (count($excludedWords) > 0) 
			{
				$folder = GitHubModule::Config()->get('absolute_temp_folder') . '/' . $moduleObject->moduleName . '/';

				$results = $this->checkDirExcludedWords($folder.'/'.$moduleObject->modulename, $excludedWords);
				
				
				if ($results && count ($results > 0)) 
				{
					$msg = "<h4>The following excluded words were found: </h4><ul>";
					foreach ($results as $file => $words) {
						foreach ($words as $word) {
							$msg .= "<li>$word in $file</li>";
						}
					}
					$msg .= '</ul>';
					
					//trigger_error ("excluded words found in files(s)");
					GeneralMethods::outputToScreen ($msg);
					UpdateModules::$unsolvedItems[$moduleObject->ModuleName] = $msg;
				}
				
			}


            foreach($files as $file) {
                //run file update

                $obj = $file::create($moduleObject);
                $obj->run();
            }

			$moduleDir = $moduleObject->Directory();

            foreach($commands as $command) {
                //run file update
                
                
                $obj = $command::create($moduleDir);
                $obj->run();
                
                
                //run command
            }

            //Update Repository description
            //$moduleObject->updateGitHubInfo(array());

            if( ! $moduleObject->add()) { die("ERROR in add"); }
            if( ! $moduleObject->commit()) { die("ERROR in commit"); }
            if( ! $moduleObject->push()) { die("ERROR in push"); }
            if( ! $moduleObject->removeClone()) { die("ERROR in removeClone"); }

            $moduleObject->addRepoToScrutinzer();
	}
    

    
    protected function renameTest($moduleObject) {
		
		$oldName = $moduleObject->Directory() . "/tests/ModuleTest.php";
		
		if ( ! file_exists($oldName) ) 
		{
			print_r ($oldName);
			return false;
		}
		
		
		
		$newName = $moduleObject->Directory() . "tests/" . $moduleObject->ModuleName . "Test.php";
		
		GeneralMethods::outputToScreen ("Renaming $oldName to $newName");
		
		unlink ($newName);
		
		rename($oldName, $newName);

		
	}

	public static function addUnsolvedProblem($moduleName, $problemString) {
		if (!isset (UpdateModules::$unsolvedItems[$moduleName]) )
		{
			UpdateModules::$unsolvedItems[$moduleName] = array();
		}
		array_push (UpdateModules::$unsolvedItems[$moduleName], $problemString);
	}
		
	protected function writeLog() {
		
		

		$mailTo = $this->Config()->get('report_email');
		$debug = $this->Config()->get('debug');
		
		$dateStr =  date("Y/m/d H:i:s");
		
		$html = '<h1> Modules checker report at ' .$dateStr . '</h1>';
		
		if (count (UpdateModules::$unsolvedItems) == 0) {
			$html .= ' <h2> No unresolved problems in modules</h2>';
		}
		
		else {
			$html .= '
				<h2> Unresolved problems in modules</h2>
			
			<table border = 1>
					<tr><th>Module</th><th>Problem</th></tr>';
		
			foreach (UpdateModules::$unsolvedItems as $moduleName => $problems) {

				foreach ($problems as $problem) {
				
					$html .= '<tr><td>'.$moduleName.'</td><td>'. $problem .'</td></tr>';
				}
			}
			$html .= '</table>';
;			
			
		}
		
		
		$logFolder = getcwd() . '/../modulechecks/logs/';
		
		$filename = $logFolder . date('U') . '.html';
		
		GeneralMethods::outputToScreen ("Writing to $filename");
		
		$result = file_put_contents ( $filename, $html);
		
		if ( ! $result )
		{
			GeneralMethods::outputToScreen ("Could not write log file");
		}
		

	
	}

    protected function checkConfigYML($module)
    {
        $configYml = ConfigYML::create($module)->reWrite();
 
    }

    private function checkFile($module, $filename) {
        $folder = GitHubModule::Config()->get('absolute_temp_folder');
        return file_exists($folder.'/'.$module.'/'.$filename);
    }

    private function checkReadMe($module) {
        return $this->checkFile($module, "README.MD");
    }

    private function checkDirExcludedWords($directory, $wordArray) {
        $filesAndFolders = scandir ($directory);

        $problem_files = array();
        foreach ($filesAndFolders as $fileOrFolder) {

            if ($fileOrFolder == '.' || $fileOrFolder == '..' || $fileOrFolder == '.git'  ) {
                continue;
            }

            $fileOrFolderFullPath = $directory . '/' . $fileOrFolder;
            if (is_dir($fileOrFolderFullPath)) {
                $dir = $fileOrFolderFullPath;
                $problem_files = array_merge ($this->checkDirExcludedWords ($dir, $wordArray) , $problem_files);
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
		

        $matchedWords = array();

		$fileName = str_replace ('////', '/',  $fileName);
		if (filesize ($fileName) == 0 ) return $matchedWords; 


        $fileContent = file_get_contents($fileName);
		if (!$fileContent) (die ("could not open $fileName</br>"));

        foreach ($wordArray as $word)  {
			

            $matches = array();
            $matchCount = preg_match_all('/' . $word . '/i', $fileContent);
            
            
           
            
            
            if ($matchCount > 0) {
                array_push ($matchedWords, $word);

            }
        }

        return $matchedWords;

    }

    private function checkUpdateTag($moduleObject) {

        $aWeekAgo = strtotime("-1 weeks");
        $tag = $moduleObject->getLatestTag();

        $commitTime = $moduleObject->getLatestCommitTime();

		if (! $commitTime) // if no commits, cannot create a tag
		{
			return false;
		}

        $createTag = false;

        if ( ! $tag ) {
            $createTag = true;
            $newTagString = '1.0.0';
        }



        else if ($tag && $commitTime > $tag['timestamp'] && $commitTime < $aWeekAgo) {
            $createTag = true;
            $tag['tagparts'][1] = $tag['tagparts'][1] + 1;
            $newTagString = trim(implode ('.', $tag['tagparts']));
        }

        if ($createTag) {

            GeneralMethods::outputToScreen ('<li> Creating new tag  '.$newTagString.' ... </li>');

            //git tag -a 0.0.1 -m "testing tag"
            $options = array (
                'a' => $newTagString,
                'm' => $this->Config()->get('tag_create_message')
            );

            $moduleObject->createTag ($options);

        }

		return true;

    }
    
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


}
