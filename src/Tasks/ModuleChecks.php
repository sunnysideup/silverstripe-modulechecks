<?php

namespace Sunnysideup\ModuleChecks\Tasks;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Model\GitHubModule;

/**
 * check if everything is in plcae for a module
 * some quick and dirty methods ....
 */

class ModuleChecks extends BuildTask
{
    protected $title = 'Check Modules on Github and Packagist';

    protected $description = 'Goes through every module on github and checks for some of the basic requirements. You will need to set your GitHub Username in the configs.';

    private static $packagist_user_name = '';

    /**
     * list of methods to run for each module
     * @var array
     */
    private static $methods_to_check = [
        'exitsOnPackagist',
        'hasReadMeFile',
        'hasLicense',
        'hasComposerFile',
        'existsOnAddOns',
    ];

    public function run($request)
    {
        Environment::increaseTimeLimitTo(3600);

        $modules = GitHubApi::get_all_repos();

        $gitUser = Config::inst()->get(GitHubModule::class, 'github_user_name');
        $packagistUser = $this->Config()->get('packagist_user_name');

        if ($gitUser && $packagistUser) {
            //all is good ...
        } else {
            user_error("make sure to set your git user name (${gitUser}) and packagist username (${packagistUser}) via the standard config system");
        }

        $count = 0;
        echo '<h1>Testing ' . count($modules) . " modules (git user: ${gitUser} and packagist user: ${packagistUser}) ...</h1>";
        $methodsToCheck = $this->Config()->get('methods_to_check');
        foreach ($modules as $module) {
            $count++;
            $failures = 0;
            echo '<h3><a href="https://github.com/' . $gitUser . '/silverstripe-' . $module . "\"></a>${count}. checking ${module}</h3>";
            foreach ($methodsToCheck as $method) {
                if (! $this->{$method}($module)) {
                    $failures++;
                    DB::alteration_message("bad response for ${method}", 'deleted');
                }
            }
            if ($failures === 0) {
                DB::alteration_message('OK', 'created');
            }
            echo '
            ';
            @ob_flush();
            @flush();
        }
        echo '----------------------------- THE END --------------------------';
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    protected function exitsOnPackagist($name)
    {
        return GeneralMethods::check_location('https://packagist.org/packages/' . $this->Config()->get('packagist_user_name') . "/${name}");
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    protected function hasLicense($name)
    {
        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get(GitHubModule::class, 'github_user_name') . '/silverstripe-' . $name . '/master/LICENSE');
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    protected function hasComposerFile($name)
    {
        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get(GitHubModule::class, 'github_user_name') . '/silverstripe-' . $name . '/master/composer.json');
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    protected function hasReadMeFile($name)
    {
        return GeneralMethods::check_location('https://raw.githubusercontent.com/' . Config::inst()->get(GitHubModule::class, 'github_user_name') . '/silverstripe-' . $name . '/master/README.md');
    }

    protected function existsOnAddOns($name)
    {
        return GeneralMethods::check_location('http://addons.silverstripe.org/add-ons/' . $this->Config()->get('packagist_user_name') . "/${name}");
    }

    /**
     * checks if a particular variable is present in the composer.json file
     *
     * @param string $name
     * @param string $variable
     * @return boolean
     */
    protected function checkForDetailsInComposerFile($name, $variable)
    {
        die('to be completed');
    }
}
