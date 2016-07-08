<?php

/**
 * does everything with one module
 *
 * see https://github.com/cpliakas/git-wrapper for info on the git wrapper module
 */



use GitWrapper\GitWrapper;
require_once '../vendor/autoload.php';

class GitHubModule extends DataObject {

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
        'ModuleName' => 'VarChar(100)',
        'Directory' => 'VarChar(255)',
        'URL' => 'Varchar(255)',
        );

    protected function IsDirGitRepo ($directory) {
        return file_exists($directory."/.git");
    }

    public function checkOrSetGitWrapper() {
        if( ! $this->git ) {
            if ($this->Directory == '') {
                user_error('Directory element must be set before using git repository commands');
            }

            if (!$this->ModuleName) {
                $this->ModuleName = substr($this->Directory, strrchr($this->Directory, '\\'));
            }

            $wrapper = new GitWrapper();

            if ($this->IsDirGitRepo($this->Directory)) {
                $this->git = $wrapper->workingCopy($this->Directory);

            }
            else {
                user_error($this->Directory. " does not appear to be git repo, cloning from URL ".$this->URL);
                $this->git = $wrapper->cloneRepository($this->URL, $this->Directory);
            }
        }
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

    }

    /**
     * adds all files to a git repo
     * @return bool
     */
    public function add() {

    }

    /**
     * adds all files to a git repo
     *
     * @return bool
     */
    public function push() {

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
