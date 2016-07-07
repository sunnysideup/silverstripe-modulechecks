<?php

/**
 * does everything with one module
 *
 * see https://github.com/cpliakas/git-wrapper for info on the git wrapper module
 */
 


use GitWrapper\GitWrapper;
require_once '../vendor/autoload.php';

class GitHubModule extends DataObject {

    private $wrapper = null;
    private $git = null;

    private static $db = array (
        'ModuleName' => 'VarChar(100)',
        'Directory' => 'VarChar(255)',
        'URL' => 'Varchar(255)',
        );

    protected function IsDirGitRepo ($directory) {
        return file_exists($directory."/.git");
    }

    public function checkOrSetGitWrapper() {
        if ($this->Directory == '') {
            die ('Directory element must be set before using git repository commands');
        }
        
        if (!$this->ModuleName) {
            $this->ModuleName = substr($this->Directory, strrchr($this->Directory, '\\'));
        }
        
        $wrapper = new GitWrapper();

        if ($this->IsDirGitRepo($this->Directory)) {
            $this->git = $wrapper->workingCopy($this->Directory);

        }
        else {
            print ($this->Directory. " does not appear to be git repo, cloning from URL ".$this->URL);
            $this->git = $wrapper->cloneRepository($this->URL, $this->Directory);
        }
        
        return $this->git;
    }

    public function pull() {
        $this->checkOrSetGitWrapper();
        if ($this->git) {
            $this->git->pull();
        }
    }
    
    public function commit($message) {
        
    }
    
    public function push() {
    
    }
    
    public function add() {
    
    }    

}
