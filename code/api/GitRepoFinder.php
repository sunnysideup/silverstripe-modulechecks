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
    public static function get_all_repos($username = '')
        {
        if(! count(self::$_modules)) {
            if(!$username) {
                $username = Config::inst()->get('GitHubModule', "git_user_name");
            }
            for($page = 0; $page < 10; $page++) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/".$username."/repos?per_page=100&page=$page");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
                curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
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
                                //check it is silverstripe module
                                $nameWithoutPrefix = preg_replace('/silverstripe/', "", $repo["name"], $limit = 1);
                                if(strlen($nameWithoutPrefix) < strlen($repo["name"])) {
                                    //is it listed yet?
                                    if(!in_array($nameWithoutPrefix, self::$_modules)) {
                                        self::$_modules[] = $nameWithoutPrefix;
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
        return self::$_modules;
    }

}
