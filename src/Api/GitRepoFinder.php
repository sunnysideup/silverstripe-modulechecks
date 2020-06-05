<?php

namespace Sunnysideup\ModuleChecks\Api;

use Config;
use DB;
use GitHubModule;
use UpdateModules;
use ViewableData;

/**
 * ### @@@@ START REPLACEMENT @@@@ ###
 * WHY: automated upgrade
 * OLD:  extends Object (ignore case)
 * NEW:  extends ViewableData (COMPLEX)
 * EXP: This used to extend Object, but object does not exist anymore. You can also manually add use Extensible, use Injectable, and use Configurable
 * ### @@@@ STOP REPLACEMENT @@@@ ###
 */
class GitRepoFinder extends ViewableData
{
    /**
     * @var string
     */
    private static $_modules = [];

    /**
     * takes the preloaded modules and
     * adds any other ones you have listed on github
     */
    public static function get_all_repos($username = '', $getNamesWithPrefix = false)
    {
        # $oauth_token = GitRepoFinder::Config()->get('github_oauth_token');

        self::get_all_repos_no_oauth($username, $getNamesWithPrefix);
        if (! self::$_modules) {
            self::get_repos_with_auth($username, $getNamesWithPrefix);
        }
        return self::$_modules;
    }

    public static function get_all_repos_no_oauth($username = '', $getNamesWithPrefix = false)
    {
        $preSelected = Config::inst()->get('UpdateModules', 'modules_to_update');
        if (is_array($preSelected) && count($preSelected)) {
            return $preSelected;
        }
        if (! $username) {
            $username = Config::inst()->get('GitHubModule', 'github_user_name');
        }
        print "<li>Retrieving List of modules from GitHub for user ${username} ... </li>";
        if (! count(self::$_modules)) {
            for ($page = 0; $page < 10; $page++) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/users/' . $username . "/repos?per_page=100&page=${page}");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

                $string = curl_exec($ch);
                // close curl resource to free up system resources
                curl_close($ch);
                $array = json_decode($string, true);
                $count = count($array);
                if ($count > 0) {
                    foreach ($array as $repo) {
                        //dont bother about forks
                        if (isset($repo['fork']) && ! $repo['fork']) {
                            //make sure we are the owners
                            if ($repo['owner']['login'] === $username) {
                                $isSSModule = (stripos($repo['name'], 'silverstripe-') !== false);
                                //check it is silverstripe module
                                if (! $getNamesWithPrefix) {
                                    $name = $repo['name'];
                                } else {
                                    $name = preg_replace('/silverstripe/', '', $repo['name'], $limit = 1);
                                }

                                //if(strlen($name) < strlen($repo["name"])) {
                                if ($isSSModule) {
                                    //is it listed yet?
                                    if (! in_array($name, self::$_modules, true)) {
                                        self::$_modules[] = $name;
                                    }
                                } else {
                                    DB::alteration_message('skipping ' . $repo['name'] . ' as it does not appear to me a silverstripe module, you can add it manually to this task, using the configs ... ');
                                }
                            } else {
                                DB::alteration_message('skipping ' . $repo['name'] . ' as it has a different owner');
                            }
                        } elseif (isset($repo['name'])) {
                            DB::alteration_message('skipping ' . $repo['name'] . ' as it is a fork');
                        }
                    }
                } else {
                    $page = 11;
                }
            }
        }
        print '<li>Found ' . count(self::$_modules) . ' modules on GitHub ... </li>';
        if (count(self::$_modules) === 0) {
            user_error('No modules found on GitHub. This is possibly because the limit of 60 requests an hour has been exceeded.');
        }

        return self::$_modules;
    }

    public static function get_repos_with_auth($username = '', $getNamesWithPrefix = false)
    {
        $preSelected = Config::inst()->get('UpdateModules', 'modules_to_update');
        if (is_array($preSelected) && count($preSelected)) {
            self::$_modules = $preSelected;
        } else {
            if ($username) {
                $gitUserName = $username;
            } else {
                $gitUserName = Config::inst()->get('GitHubModule', 'github_user_name');
            }
            print "<li>Retrieving List of modules from GitHub for user ${username} ... </li>";
            if (! count(self::$_modules)) {
                $url = 'https://api.github.com/users/' . trim($gitUserName) . '/repos';
                $array = [];
                for ($page = 0; $page < 10; $page++) {
                    $data = [
                        'per_page' => 100,
                        'page' => $page,
                    ];

                    $method = 'GET';
                    $ch = curl_init($url);
                    $header = 'Content-Type: application/json';

                    if ($method === 'GET') {
                        $url .= '?' . http_build_query($data);
                    }

                    $gitApiUserName = trim(GitHubModule::Config()->get('git_api_login_username'));
                    $gitUserName = trim(GitHubModule::Config()->get('github_user_name'));
                    $gitApiUserPassword = trim(GitHubModule::Config()->get('git_api_login_password'));

                    $gitApiAccessToken = trim(GitHubModule::Config()->get('git_personal_access_token'));
                    if (trim($gitApiAccessToken)) {
                        $gitApiUserPassword = $gitApiAccessToken;
                    }

                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [$header]);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt(
                        $ch,
                        CURLOPT_USERAGENT,
                        'sunnysideupdevs'
                    );

                    if (isset($gitApiUserName) && isset($gitApiUserPassword)) {
                        curl_setopt($ch, CURLOPT_USERPWD, $gitApiUserName . ':' . $gitApiUserPassword);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    }

                    $curlResult = curl_exec($ch);

                    if (! $curlResult) {
                        GeneralMethods::output_to_screen('Could not retrieve list of modules from GitHub');

                        UpdateModules::$unsolvedItems['all'] = 'Could not retrieve list of modules from GitHub';
                        die('');
                    }

                    $array = array_merge($array, json_decode($curlResult));
                }

                $modules = [];

                if (count($array) > 0) {
                    foreach ($array as $repo) {
                        // see http://stackoverflow.com/questions/4345554/convert-php-object-to-associative-array
                        $repo = json_decode(json_encode($repo), true);

                        //dont bother about forks
                        if (isset($repo['fork']) && ! $repo['fork']) {
                            //make sure we are the owners

                            if ($repo['owner']['login'] === $gitUserName) {
                                $isSSModule = (stripos($repo['name'], 'silverstripe-') !== false);
                                //check it is silverstripe module

                                if (! $getNamesWithPrefix) {
                                    $name = $repo['name'];
                                } else {
                                    $name = preg_replace('/silverstripe/', '', $repo['name'], $limit = 1);
                                }

                                //if(strlen($name) < strlen($repo["name"])) {
                                if ($isSSModule) {
                                    //is it listed yet?
                                    if (! in_array($name, $modules, true)) {
                                        array_push($modules, $name);
                                    }
                                } else {
                                    GeneralMethods::output_to_screen('skipping ' . $repo['name'] . ' as it does not appear to me a silverstripe module');
                                }
                            } else {
                                GeneralMethods::output_to_screen('skipping ' . $repo['name'] . ' as it has a different owner');
                            }
                        } elseif (isset($repo['name'])) {
                            DB::alteration_message('skipping ' . $repo['name'] . ' as it is a fork');
                        }
                    }
                }
                self::$_modules = $modules;
            }
        }
        return self::$_modules;
    }
}
