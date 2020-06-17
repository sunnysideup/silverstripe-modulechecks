<?php

namespace Sunnysideup\ModuleChecks\Api;

use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DB;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Model\Module;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
use Sunnysideup\Flush\FlushNow;

class GitHubApi extends BaseObject
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
        # $oauth_token = GitHubApi::Config()->get('github_oauth_token');

        self::get_all_repos_no_oauth($username, $getNamesWithPrefix);
        if (! self::$_modules) {
            self::get_repos_with_auth($username, $getNamesWithPrefix);
        }
        return self::$_modules;
    }

    public static function get_all_repos_no_oauth($username = '', $getNamesWithPrefix = false)
    {
        $preSelected = Config::inst()->get(UpdateModules::class, 'modules_to_update');
        if (is_array($preSelected) && count($preSelected)) {
            return $preSelected;
        }
        if (! $username) {
            $username = Config::inst()->get(BaseObject::class, 'github_user_name');
        }
        FlushNow::do_flush('Retrieving List of modules from GitHub for user ' . $username . ' without AUTH... ');
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
                        self::retrieve_repos($repo, $username, $getNamesWithPrefix);
                    }
                } else {
                    $page = 11;
                }
            }
        }
        FlushNow::do_flush('Found ' . count(self::$_modules) . ' modules on GitHub ...');
        if (count(self::$_modules) === 0) {
            user_error('No modules found on GitHub. This is possibly because the limit of 60 requests an hour has been exceeded.');
        }

        return self::$_modules;
    }

    public static function get_repos_with_auth($repo, $username, $getNamesWithPrefix = false)
    {
        $preSelected = Config::inst()->get(UpdateModules::class, 'modules_to_update');
        if (is_array($preSelected) && count($preSelected)) {
            self::$_modules = $preSelected;
        } else {
            if ($username) {
                $gitUserName = $username;
            } else {
                $gitUserName = Config::inst()->get(BaseObject::class, 'github_user_name');
            }
            FlushNow::do_flush('Retrieving List of modules from GitHub for user ' . $username . ' with AUTH ... ');
            if (! count(self::$_modules)) {
                $url = 'https://api.github.com/users/' . trim($gitUserName) . '/repos';
                $array = [];
                for ($page = 0; $page < 10; $page++) {
                    $data = [
                        'per_page' => 100,
                        'page' => $page,
                    ];

                    $method = 'GET';
                    $curlResult = self::run_curl_results($url, $method, $data);

                    if (! $curlResult) {
                        FlushNow::do_flush('Could not retrieve list of modules from GitHub');

                        UpdateModules::$unsolvedItems['all'] = 'Could not retrieve list of modules from GitHub';
                    }
                    $array = json_decode($curlResult, true);
                    if (count($array) > 0) {
                        foreach ($array as $repo) {
                            self::retrieve_repos($repo, $username, $getNamesWithPrefix);
                        }
                    }
                }
            }
        }
        return self::$_modules;
    }

    public static function has_file_on_git_hub(Module $repo, string $fileName): bool
    {
        $rawURL = self::get_file_location($repo, $fileName);
        return FileMethods::check_location_exists($rawURL);
    }

    public static function get_file_location(Module $repo, string $fileName) : string
    {
        $gitUserName = Config::inst()->get(BaseObject::class, 'github_user_name');

        return 'https://raw.githubusercontent.com/' . $gitUserName . '/' . $repo->ModuleName . '/' . $repo->getBranch() . '/' . $fileName;

    }

    /**
     * retrieves a raw file from Github
     *
     * @return string
     */
    public static function get_raw_file_from_github(Module $repo, string $fileName): string
    {
        $rawURL = self::get_file_location($repo, $fileName);
        if(! FileMethods::check_location_exists($rawURL)) {
            FlushNow::do_flush('Could not find ' . $rawURL);
            return '';

        }
        $content = '';
        $file = @fopen($rawURL, 'r');
        if($file) {
            while (! feof($file)) {
                $content .= fgets($file);
            }
            fclose($file);
        }
        else {
            $content = @file_get_contents($rawURL);
        }
        if (! $content) {
            FlushNow::do_flush('Could not read ' . $rawURL);
            return '';
        }

        return $content;
    }

    public static function api_call(string $moduleName, array $data, ?string $gitAPIcommand = '', ?string $method = 'GET')
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        FlushNow::do_flush('Running Git API command ' . $gitAPIcommand . ' using ' . $method . ' method...');
        $gitUserName = Config::inst()->get(BaseObject::class, 'github_user_name');
        $url = 'https://api.github.com/:repos/' . trim($gitUserName) . '/:' . trim($moduleName);
        if (trim($gitAPIcommand)) {
            $url .= '/' . trim($gitAPIcommand);
        }

        $curlResult = self::run_curl_results($url, $method, $jsonData);

        print_r($url);
        print_r('<br/>');
        print_r($curlResult);
        return $curlResult;
    }

    protected static function run_curl_results(string $url, string $method, array $jsonData)
    {
        $method = trim(strtoupper($method));

        $gitApiUserName = trim(Config::inst()->get(BaseObject::class, 'git_api_login_username'));
        $gitApiUserPassword = trim(Config::inst()->get(BaseObject::class, 'git_api_login_password'));

        $gitApiAccessToken = trim(Config::inst()->get(BaseObject::class, 'git_personal_access_token'));
        if (trim($gitApiAccessToken)) {
            $gitApiUserPassword = $gitApiAccessToken;
        }

        $header = 'Content-Type: application/json';
        $ch = curl_init($url);
        if ($method === 'GET') {
            $url .= '?' . http_build_query($jsonData);
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

        if ($gitApiUserName && $gitApiUserPassword) {
            curl_setopt($ch, CURLOPT_USERPWD, $gitApiUserName . ':' . $gitApiUserPassword);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }
        $curlResult = curl_exec($ch);
        if (! $curlResult) {
            $msg = 'curl exectution failed';
            FlushNow::do_flush($msg);
            UpdateModules::$unsolvedItems['none'] = $msg;
        }
        return curl_exec($ch);
    }

    protected static function retrieve_repos($repo, $username, $getNamesWithPrefix)
    {
        //dont bother about forks
        if (isset($repo['fork']) && ! $repo['fork']) {
            //make sure we are the owners
            if ($repo['owner']['login'] === $username) {
                $isSSModule = (stripos($repo['name'], 'silverstripe-') !== false);
                //check it is silverstripe module
                if ($getNamesWithPrefix) {
                    $prefix = 'silverstripe';
                    if (substr($repo['name'], 0, strlen($prefix)) === $prefix) {
                        $repo['name'] = substr($repo['name'], strlen($prefix));
                    }
                    $name = $repo['name'];
                } else {
                    $name = $repo['name'];
                }

                //if(strlen($name) < strlen($repo["name"])) {
                if ($isSSModule) {
                    //is it listed yet?
                    if (! in_array($name, self::$_modules, true)) {
                        self::$_modules[$name] = [
                            'name' => $name ?? 'tba',
                            'Description' => $repo['description'] ?? 'tba',
                            'Created' => $repo['created_at'] ?? 'tba',
                            'LastEdited' => $repo['updated_at'] ?? 'tba',
                            'ForksCount' => $repo['forks_count'] ?? 'tba',
                            'DefaultBranch' => $repo['default_branch'] ?? 'tba',
                            'Private' => $repo['private'] ?? 'tba',
                            'HomePage' => $repo['private'] ?? 'tba',
                        ];
                    }
                    FlushNow::do_flush('Listing '.$repo['name'], 'created');
                } else {
                    FlushNow::do_flush('skipping ' . $repo['name'] . ' as it does not appear to me a silverstripe module, you can add it manually to this task, using the configs ... ', 'obsolete');                }
            } else {
                FlushNow::do_flush('skipping ' . $repo['name'] . ' as it has a different owner', 'obsolete');
            }
        } elseif (isset($repo['name'])) {
            FlushNow::do_flush('skipping ' . $repo['name'] . ' as it is a fork', 'obsolete');
        }
    }
}
