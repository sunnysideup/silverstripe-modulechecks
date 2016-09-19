<?php



class GitRepoFinder extends Object
{

    /**
     *
     *
     *
     * @var string
     */
    private static $_modules = array();

    

    /**
     * takes the preloaded modules and
     * adds any other ones you have listed on github
     */

    public static function get_all_repos($username = '', $getNamesWithPrefix = false) {
        $oauth_token = GitRepoFinder::Config()->get('github_oauth_token');
        if ($oauth_token) {
            return GitRepoFinder::get_all_repos_oauth($username, $getNamesWithPrefix);
        }
        else {
            return GitRepoFinder::get_all_repos_no_oauth($username, $getNamesWithPrefix);
        }
    }

    public static function get_all_repos_no_oauth($username = '', $getNamesWithPrefix = false)
    
        {
        /*
         * To do: Add OAuth capability to get around API limit - Check Git Wrapper Module
         * 
         * */
        if(!$username) {
            $username = Config::inst()->get('GitHubModule', "git_user_name");
            print("<li></li>");
        }         
        print "<li>Retrieving List of modules from GitHub for user $username ... </li>";            
        if(! count(self::$_modules)) {

            for($page = 0; $page < 10; $page++) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/".$username."/repos?per_page=100&page=$page");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
                curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                
                
                $string = curl_exec($ch);
                // close curl resource to free up system resources
                curl_close($ch);
                $array = json_decode($string, true);
                $count = count($array);
                if($count > 0 ) {
                    foreach($array as $repo) {
                        //dont bother about forks
                        if(isset($repo["fork"]) && !$repo["fork"]) {
                            //make sure we are the owners
                            if($repo["owner"]["login"] == $username) {

                                $isSSModule =  ( stripos($repo["name"], 'silverstripe-')  !== false );
                                //check it is silverstripe module
                                if (!$getNamesWithPrefix) {
                                    $name = $repo["name"];                                    
                                }
                                else {
                                    $name = preg_replace('/silverstripe/', "", $repo["name"], $limit = 1);                                    
                                }
                                
                                //if(strlen($name) < strlen($repo["name"])) {
                                if($isSSModule) {
                                    //is it listed yet?
                                    if(!in_array($name, self::$_modules)) {
                                        self::$_modules[] = $name;
                                    }
                                }
                                else {
                                    DB::alteration_message("skipping ".$repo["name"]." as it does not appear to me a silverstripe module, you can add it manually to this task, using the configs ... ");
                                }
                            }
                            else {
                                DB::alteration_message("skipping ".$repo["name"]." as it has a different owner");
                            }
                        }
                        elseif(isset($repo["name"])) {
                            DB::alteration_message("skipping ".$repo["name"]." as it is a fork");
                        }
                    }
                }
                else {
                    $page = 11;
                }
            }
        }
        print "<li>Found ".count(self::$_modules)." modules on GitHub ... </li>"; 
        if (count(self::$_modules)==0) {
            user_error ("No modules found on GitHub. This is possibly because the limit of 60 requests an hour has been exceeded.");
        }
        return self::$_modules;
    }

    public static function get_all_repos_oauth ($username = '', $getNamesWithPrefix = false) {

    /*
            $header[]         = 'Content-Type: application/x-www-form-urlencoded';

            curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);
            curl_setopt($ch, CURLOPT_POST,        true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode("oauth_consumer_key=example.com&
           oauth_signature_method=RSA-SHA1&
           oauth_signature=wOJIO9A2W5mFwDgiDvZbTSMK%2FPY%3D&
           oauth_timestamp=137131200&
           oauth_nonce=4572616e48616d6d65724c61686176&
           oauth_version=1.0"));
     *
     */
    }
}