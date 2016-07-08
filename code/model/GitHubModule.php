<?php

/**
 * does everything with one module
 *
 * see https://github.com/cpliakas/git-wrapper for info on the git wrapper module
 */



use GitWrapper\GitWrapper;
require_once '../vendor/autoload.php';

class GitHubModule extends DataObject {


    private static $github_account_base_url = '';

    //private static $github_account_base_ssh = 'git@github.com:sunnysideup/';

    /**
     *
     *
     *
     * @var string
     */
    private static $git_user_name = '';

    /**
     * where the git module is temporary
     * cloned and fixed up
     *
     *
     * @var string
     */
    private static $temp_folder = '';

    /**
     *
     *
     * @var GitWrapper
     */
    private $wrapper = null;

    /**
     *
     *
     * @var git module
     */
    private $git = null;

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

    public function Directory () {
        $tempFolder = $this->Config()->get('temp_folder');
        return $tempFolder.'/'.$this->ModuleName;
    }

    public function URL () {
        $username = $this->Config()->get('git_user_name');
        return 'https://github.com/'.$username.'/'.$this->ModuleName;
    }

    protected function IsDirGitRepo ($directory) {
        return file_exists($directory."/.git");
    }
    


    public function checkOrSetGitWrapper() {
        if( ! $this->git ) {
            if ($this->ModuleName == '') {
                user_error('ModuleName element must be set before using git repository commands');
            }

            $wrapper = new GitWrapper();

            if ($this->IsDirGitRepo($this->Directory())) {
                $this->git = $wrapper->workingCopy($this->Directory());

            }
            else {
                user_error($this->Directory(). " does not appear to be git repo");
            }
        }
        
        $this->git->config("push.default", "simple");
        $this->git->config("user.name", $this->Config()->get('git_user_name'));
        //git config user.name "Billy Everyteen"
        
        return $this->git;
    }

    /**
     * pulls a git repo
     *
     * @return bool
     */
    public function pull() {
        $git = $this->checkOrSetGitWrapper();
        if ($git) {
            $git->pull();
            return true;
        }
        return false;
    }

    /**
     * commits a git repo
     *
     * @param string $message
     *
     * @return bool
     */
    public function commit($message) {
        $git = $this->checkOrSetGitWrapper();
        if ($git) {
            
            $git->commit($message);
    
            return true;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     * @return bool
     */
    public function add() {
        $git = $this->checkOrSetGitWrapper();
        if ($git) {
            
            $git->add("*");
    
            return true;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     *
     * @return bool
     */
    public function push() {
        $git = $this->checkOrSetGitWrapper();
        if ($git) {
            
            $git->push("origin", "master");
    
            return true;
        }
        return false;
    }

    /**
     * adds all files to a git repo
     *
     * @return bool
     */
    public function cloneRepo() {
        $username = $this->Config()->get('git_user_name');
        $gitURL = $this->Config()->get('github_account_base_url');
        
        if ($this->IsDirGitRepo($this->Directory())) {
            $this->removeClone();
        }
        
        $wrapper = new GitWrapper();
        print '<li>Cloning '.$this->ModuleName.' into '. $this->Directory().' ...  </li>';
        $this->git = $wrapper->cloneRepository($gitURL.'/'.$username.'/'.$this->ModuleName, $this->Directory());
    }

    /**
     * removes a cloned repo
     *
     * 
     */
    public function removeClone() {
        print "<li>Removing ".$this->Directory()." and all its contents ... </li>";
        $this->git = null;
        return exec ("rm -rf ".  $this->Directory());
    }


    public static function get_git_hub_module($moduleName) {
        $moduleName = trim($moduleName);
        $filter = array('ModuleName' => $moduleName);
        $gitHubModule = GitHubModule::get()->filter($filter)->first();
        if (!$gitHubModule) {
            $gitHubModule = GitHubModule::create($filter);
            $gitHubModule->write();
        }

        return $gitHubModule;
    }



}
