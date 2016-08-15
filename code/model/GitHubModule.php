<?php

/**
 * does everything with one module
 *
 * see https://github.com/cpliakas/git-commsWrapper for info on the git commsWrapper module
 */



use GitWrapper\GitWrapper;
require_once '../vendor/autoload.php';

class GitHubModule extends DataObject {


    /**
     * e.g.
     * @var string
     */
    private static $github_account_base_url = '';

    /**
     * e.g. boss
     * @var string
     */
    private static $github_user_name = '';

    /**
     *
     *
     * @var GitcommsWrapper
     */
    private static $git_user_email = '';

    /**
     * where the git module is temporary
     * cloned and fixed up
     * should be an absolute_path
     *
     * @var string
     */
    private static $path_to_private_key = '';

    /**
     * where the git module is temporary
     * cloned and fixed up
     * should be an absolute_path
     *
     * @var string
     */
    private static $absolute_temp_folder = '';

    /**
     * wrapper also relates to one git hub repo only!!!!
     *
     * @var GitcommsWrapper
     */
    protected $commsWrapper = null;

    /**
     *
     *
     * @var git module
     */
    protected $gitRepo = null;

    private static $db = array (
        'ModuleName' => 'VarChar(100)'
    );

    private static $indexes = array (
        'ModuleName' => true,
    );


    private static $casting = array(
        'Directory' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
    );

    public function getDirectory() {
        return $this->Directory();
    }

    /**
     * absolute path
     * @return string | null
     */
    public function Directory () {
        $tempFolder = $this->Config()->get('absolute_temp_folder');
        if($this->ModuleName) {
            $folder = $tempFolder.'/'.$this->ModuleName;
            if(file_exists($folder)) {
                if(file_exists($folder)) {
                    return $folder;
                }
            } else {
                mkdir($folder);
                if(file_exists($folder)) {
                    return $folder;
                }
            }
        }
    }

    public function getURL()
    {
        return $this->URL();
    }


    function LongModuleName()
    {
        return $this->Config()->get('git_user_name').'/'.$this->ModuleName;
    }


    function MediumModuleName()
    {
        return $this->ModuleName;
    }

    /**
     * @todo: check that silverstripe- is at the start of string.
     * @return string
     */ 
    function ShortModuleName()
    {
        return str_replace('silverstripe-', '', $this->ModuleName);
    }

    /**
     * check if URL exists and returns it
     * @var string | null
     */
    public function URL () {
        $username = $this->Config()->get('github_user_name');
        return 'https://github.com/'.$username.'/'.$this->ModuleName;
    }

    protected function IsDirGitRepo ($directory) {
        return file_exists($directory."/.git");
    }


    /**
     *
     * @param bool (optional) $forceNew - create a new repo and ditch all changes
     * @return Git Repo Object
     */
    public function checkOrSetGitCommsWrapper($forceNew = false) {
        //if there is a wrapper the is also a repo....
        if( ! $this->gitRepo ) {

            //basic check
            if ($this->ModuleName == '') {
                user_error('ModuleName element must be set before using git repository commands');
            }

            //create comms
            $this->commsWrapper = new GitWrapper();

            // Stream output of subsequent Git commands in real time to STDOUT and STDERR.
            if(Director::is_cli()) {
                $this->commsWrapper->streamOutput();
            }


            if( ! $this->Config()->get('path_to_private_key')) {
                user_error("We recommend you set private key");
            }
            // Optionally specify a private key other than one of the defaults.
            $this->commsWrapper->setPrivateKey($this->Config()->get('path_to_private_key'));

            //if directory exists, return existing repo,
            //otherwise clone it....
            if($this->IsDirGitRepo($this->Directory())) {
                if($forceNew) {
                    $this->removeClone();
                    return $this->checkOrSetGitCommsWrapper(false);
                }
                $this->gitRepo = $this->commsWrapper->workingCopy($this->Directory());
            } else {
                GeneralMethods::output_to_screen("cloning ... ".$this->fullGitURL(),'created');
                $this->gitRepo = $this->commsWrapper->cloneRepository(
                    $this->fullGitURL(),
                    $this->Directory()
                );
            }
            $this->gitRepo->config("push.default", "simple");
            $this->gitRepo->config("user.name", $this->Config()->get('github_user_name'));
            $this->gitRepo->config("user.email", $this->Config()->get('git_user_email'));
            $this->commsWrapper->git('config -l');
        }
        return $this->gitRepo;
    }

    /**
     * @var string
     */
    function fullGitURL()
    {
        $username = $this->Config()->get('git_user_name');
        $gitURL = $this->Config()->get('github_account_base_url');
        return 'git@github.com:/'.$username.'/'.$this->ModuleName.'.git';
    }

    /**
     * pulls a git repo
     *
     * @return bool | this
     */
    public function pull() {
        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            try {
                 $git->pull();
            }
            catch (GitWrapper\GitException $e) {
                print_r($e);
                throw $e;
            }


            //GeneralMethods::output_to_screen($git->getOutput());
            return $this;
        }
        return false;
    }

    /**
     * commits a git repo
     *
     * @param string $message
     *
     * @return bool | this
     */
    public function commit($message = '') {
        if(!$message) {
            $message = 'fix ups';
        }
        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {

            try {
                $git->commit($message);
            }
            catch (Exception $e) {
                $errStr = $e->getMessage();
                if (stripos($errStr, 'nothing to commit') === false) {
                    print_r($e);
                    throw $e;
                }
                else {
                    GeneralMethods::output_to_screen('No changes to commit');
                }
            }
            //GeneralMethods::output_to_screen($git->getOutput());

            return $this;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     * @return bool | this
     */
    public function add() {

        GeneralMethods::output_to_screen('Adding new files to '.$this->ModuleName.' ...  ' ,"created");

        $git = $this->checkOrSetGitcommsWrapper();
        if ($git) {
            try {
                $git->add(".");
            }
            catch (GitWrapper\GitException $e) {
                $errStr = $e->getMessage();
                if (stripos($errStr, 'did not match any files') === false) {
                    print_r($e);
                    throw $e;
                }
                else {
                   GeneralMethods::output_to_screen('No new files to add to $module. ');
                }
            }

            //GeneralMethods::output_to_screen($git->getOutput());

            return $this;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     *
     * @return bool | this
     */
    public function push() {
        GeneralMethods::output_to_screen('Pushing files to '.$this->ModuleName.' ...  ' ,"created");

        $git = $this->checkOrSetGitcommsWrapper();
        if ($git) {
            try {
                $git->push();

            }
            catch (Exception $e) {
                $git->getOutput();
                print_r($e);
                throw $e;
            }

            return $this;
        }
        return false;
    }

    /**
     * removes a cloned repo
     *
     *
     */
    public function removeClone() {
        $dir = $this->Directory();
        GeneralMethods::output_to_screen('Removing '.$dir.' and all its contents ...  ' ,"created");
        $this->gitRepo = null;
        FileSystem::removeFolder($dir); // removes contents but not the actual folder
        rmdir ($dir);
        return ! file_exists($dir);
    }

    
    /**
     * retrieves a raw file from Github
     *
     * @return string | bool
     */

    public function getRawFileFromGithub($fileName) {
        
        $gitUserName = $this->Config()->get('git_user_name');
        $branch = 'master';

        $rawURL = 'https://raw.githubusercontent.com/' . $gitUserName . '/' . $this->ModuleName . '/' . $branch . '/' . $fileName;

        set_error_handler(array($this, 'catchFopenWarning'), E_WARNING);
        $file = fopen($rawURL, 'r');
        restore_error_handler();
        
        if ( ! $file){
            GeneralMethods::outputToScreen('<li>Could not find ' . $rawURL . '</li>');
            return false;
        }
        $content = '';
        while(! feof($file))
        {
            $content .= fgets($file);
        }
        fclose($file);
        return $content;
    }

    /*
     * 
     * */

    private function catchFopenWarning($errno, $errstr) {
        //
    }


    public static function get_or_create_github_module($moduleName) {
        $moduleName = trim($moduleName);
        $filter = array('ModuleName' => $moduleName);
        $gitHubModule = GitHubModule::get()->filter($filter)->first();
        if ( ! $gitHubModule) {
            $gitHubModule = GitHubModule::create($filter);
            $gitHubModule->write();
        }

        return $gitHubModule;
    }




}
