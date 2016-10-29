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
        'ModuleName' => 'VarChar(100)',
        'Description' => 'VarChar(300)'
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

    public function getDescription() {
        return $this->Description();
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


    function ModuleNameFirstLetterCapital() {
        $shortName = $this->ShortModuleName();

        $firstLetterCapitalName = str_replace ('_', ' ', $shortName);
        $firstLetterCapitalName = str_replace ('-', ' ', $firstLetterCapitalName);


        return strtolower ($firstLetterCapitalName);
    }

    function setDescription($str) {
        $this->Description = trim($str);
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
        //check if one has been created already...
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

                $this->gitRepo = null;
                $cloneAttempts = 0;
                while ( ! $this->gitRepo ) {
                    $cloneAttempts ++;
                    if ($cloneAttempts == 4) {
                        user_error ('Failed to clone module ' . $this->LongModuleName() . ' after ' . ($cloneAttempts  - 1). ' attemps.', E_USER_ERROR);
                    }
                    try {
                        $this->commsWrapper->setTimeout(240); //Big modules need a longer timeout
                        $this->gitRepo = $this->commsWrapper->cloneRepository(
                            $this->fullGitURL(),
                            $this->Directory()
                        );
                        $this->commsWrapper->setTimeout(60);
                    }
                    catch (Exception $e) {
                        if (strpos($e->getMessage(), 'already exists and is not an empty directory') !== false) {
                            user_error ($e->getMessage(), E_USER_ERROR);
                        }

                        GeneralMethods::outputToScreen ('<li>Failed to clone repository: ' .  $e->getMessage() . '</li>');
                        GeneralMethods::outputToScreen ('<li>Waiting 8 seconds to try again ...: </li>');
                        $this->removeClone();
                        sleep (8);
                    }
                }
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
            $pushed = false;
            $pushAttempts = 0;
            while ( ! $pushed ) {
                $pushAttempts ++;
                try {
                    $git->push();
                    $pushed = true;
                }
                catch (Exception $e) {

                    if ($pushAttempts == 3) {
                        $git->getOutput();
                        print_r($e);
                        throw $e;
                    }
                    else {
                        GeneralMethods::outputToScreen ('<li>Failed to push repository: ' .  $e->getMessage() . '</li>');
                        GeneralMethods::outputToScreen ('<li>Waiting 8 seconds to try again ...: </li>');
                        sleep (8);
                    }
                }
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
        //rmdir ($dir);
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
     * This function is just used to suppression of warnings
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


    public function getLatestCommitTime() {
        // equivalent to git log -1 --format=%cd .

        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            $options = array (
                'format' => "%cd",
                '1' => true
            );

            $result = $git->log ($options);
            if ($result) {
                return (strtotime($result));
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getLatestTag() {
        $git = $this->checkOrSetGitCommsWrapper();
        if ($git) {
            $options = array (
                'tags' => true,
                'simplify-by-decoration' => true,
                'pretty' => 'format:%ai %d'
            );

            $cwd = getcwd();
            chdir($this->Directory);

            $result = $git->log($options);




            chdir($cwd);

            $resultLines  =  explode ("\n", $result->getOutput());

            // 2016-10-14 12:29:08 +1300 (HEAD -> master, tag: 2.3.0, tag: 2.2.0, tag: 2.1.0, origin/master, origin/HEAD)\
            // or
            // 2016-08-29 17:18:22 +1200 (tag: 2.0.0)
            //print_r($resultLines);


            if (count($resultLines) == 0) {
                return false;
            }

            $latestTimeStamp = 0;
            $latestTag = false;
            foreach ($resultLines as $line) {
                $isTagInLine = (strpos ($line, 'tag') !== false);
                if ($isTagInLine) {
                    $tagStr = trim(substr($line, 25));
                    $dateStr = trim(substr($line, 0, 26));


                    //extract tag numbers from $tagStr

                    $matches = array();
                    // print_r ("original!!! " .  $tagStr);
                    $result = preg_match_all('/tag: \d{1,3}.\d{1,3}.\d{1,3}/', $tagStr, $matches);
                    if ($result === false)
                    {
                        continue;
                    }
                    elseif ($result > 1)
                    {
                        $tagStr = $matches[0][0];
                    }
                    //print_r ($matches);

                    $tagStr  = str_replace('(', '', $tagStr);
                    $tagStr  = str_replace(')', '', $tagStr);
                    $timeStamp = strtotime ($dateStr);

                    if ($latestTimeStamp < $timeStamp) {
                        $latestTimeStamp = $timeStamp;
                        $latestTag = $tagStr;
                    }
                }
            }

        }

        if (isset($latestTag) && $latestTag) {
            $latestTag = str_replace('tag:', '', $latestTag) ;


            $tagParts = explode ('.', $latestTag);

            if (count($tagParts) != 3) return false;

            return array (
                'tagstring' => $latestTag,
                'tagparts' => $tagParts,
                'timestamp' => $latestTimeStamp);

        }
        else {
            return false;
        }
    }

    public function createTag($tagCommandOptions)
    {
        $this->gitRepo->tag($tagCommandOptions);
        $this->gitRepo->push(array('tags' => true));

    }

    public function updateGitHubInfo($array) {
        // see https://developer.github.com/v3/repos/#edit
        $defaultValues =array(
            'name' => $this->LongModuleName(),
            'private' => false,
            'has_wiki' => false,
            'has_issues' => true,
            'has_downloads' => true,
            'homepage' => 'http://ssmods.com/'
            );

        if ($this->Description) {
            $array['description'] = $this->Description;
        }

        foreach ($defaultValues as $key=>$value) {
            if (! isset($array[$key])) {
                $array[$key]  = $value;
            }
        }

        GeneralMethods::OutputToScreen('updating Git Repo information ...');

        $this->gitApiCall($array, '',  'PATCH');
    }


    protected function gitApiCall($data, $gitAPIcommand = '', $method = 'GET') {

        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        GeneralMethods::OutputToScreen('Running Git API command ' .$gitAPIcommand. ' using ' .$method. ' method...');

        $gitUserName = $this->Config()->get('git_user_name');
        $url = 'https://api.github.com/:repos/' . trim($gitUserName) . '/:' . trim($this->ModuleName);

        if (trim($gitAPIcommand)) {
            $url .= '/' . trim($gitAPIcommand);
        }

        $method = trim(strtoupper($method));
        $ch = curl_init($url);
        $header = "Content-Type: application/json";

        if ($method == 'GET') {
            $url .= '?'.http_build_query($data);
        }

        $gitApiUserName = trim($this->Config()->get('git_api_login_username'));
        $gitApiUserPassword = trim($this->Config()->get('git_api_login_password'));

        $gitApiAccessToken = trim($this->Config()->get('git_personal_access_token'));
        if (trim($gitApiAccessToken)) {
            $gitApiUserPassword = $gitApiAccessToken;
        }


        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Silverstripe-update-module-module');


        if (isset($gitApiUserName) && isset($gitApiUserPassword)) {
            curl_setopt($ch, CURLOPT_USERPWD, $gitApiUserName . ':' . $gitApiUserPassword);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        if ($method == 'POST' ) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }

        $curlResult = curl_exec($ch);

        if ( ! $curlResult ){
            die ('Curl failed.');
        }

        print_r($url);
        print_r('<br/>');
        print_r($curlResult );

        die();

        return $curlResult;
    }
}
