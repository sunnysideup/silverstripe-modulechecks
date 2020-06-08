<?php

namespace SilverStripe\View;

use Exception;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Dev\Debug;
use SilverStripe\Dev\Deprecation;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\SSViewer;
use UnexpectedValueException;
use Sunnysideup\ModuleChecks\Model\GitHubModule;

class BaseObject
{

    use Extensible;
    use Injectable;
    use Configurable;

    private const CHECKS = [
        'github_account_base_url',
        'github_user_name',
        'github_user_email',
        'path_to_private_key',
        'absolute_temp_folder'
    ];

    protected static $inst = null;

    public static function inst() : BaseObject
    {
        if(! self::$inst) {
            self::$inst = Injector::inst()->get(BaseObject::class);
            self::$inst->areWeReady();
        }
        self::$inst;
    }

    /**
     * list of classes to run,  in the right order
     * @var array
     */
    private static $core_classes = [
        'ChecksAbstract',
        'FilesToAddAbstract',
        'UpdateComposerAbstract',
        'ShellCommandsAbstract',
        'OtherCommands',
    ];

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
     * @var string
     */
    private static $github_user_email = '';

    /**
     * @var string
     */
    private static $path_to_private_key = '~/.ssh/id_rsa';


    /**
     *
     * @var string
     */
    private static $packagist_user_name = '';

    private static $scrutinizer_api_key = '';

    /**
     * where the git module is temporary
     * cloned and fixed up
     * should be an absolute_path
     *
     * @var string
     */
    private static $absolute_temp_folder = '/var/www/temp/';

    /**
     *
     * @var string
     */
    private static $license_type = 'BSD-3-Clause';

    /**
     * Auto tag creation delay, using strtotime format. defaults to a week ago if not set
     * @var string
     */
    private static $tag_delay = '-1 weeks';

    /**
     *  message for auto-created tags (git tag!)
     * @var string
     */
    private static $tag_create_message = 'Auto-created tag.';


     /**
      * log folder is needed to write log file with unresolved problems, leave out
      * not to write log file
      * @var string
      */
    private static $logfolder = '/var/www/moduletools/log/';

    /**
     *  Words to check for accross all files in test modules. Produces warnings
     *  when matches are found. Regex fomat. Leave empty not to do any checks
     */
    private static $excluded_words= [

    ];

    private static $home_page = 'https://silverstripe.org';

    private static $debug = true;


    public function areWeReady()
    {
        foreach(self::CHECKS as $check) {
            $value = $this->Config()->get($check);
            if(! $value) {
                user_error('You need to set '.$check.' as a private static in BaseObject');
                return false;
            }
            if(strpos($value, '/') !== false) {
                if(! file_exists($value)) {
                    user_error('The following dir/file can not be found! '.$value);
                    return false;
                }
            }
        }
        return true;
    }


    protected $availableCommandsList = [];

    public function availableCommands() : array
    {
        if (! count($this->availableCommandsList)) {
            foreach($this->Config()->get('core_classes') as $class) {
                $classes = ClassInfo::subclassesFor($class, false);
                foreach($classes as $class) {
                    $obj = Injector::inst()->get($class);
                    if (Config::inst()->get($class, 'enabled')) {
                        $this->availableCommandsList[$class] = $obj;
                    }
                }
            }
        }

        return $this->availableCommandsList;
    }


    public function availableCommandsForDropdown() : array
    {
        $list = $this->availableCommands();
        $array = [];
        foreach($list as $class => $details) {
            $array[$class] = $details->getDescription();
        }

        return $array;

    }
    protected $availableRepos = [];

    public function getAvailableRepos() : array
    {
        if (! count($this->availableRepos)) {
            $list = GitHubModule::get();
            foreach($list as $obj) {
                $this->availableRepos[$obj->ModuleName] = $obj;
            }
        }

        return $this->availableRepos;
    }


    public function getAvailableReposForDropdown() : array
    {
        $list = $this->getAvailableRepos();
        $array = [];
        foreach($list as $obj) {
            $array[$obj->ModuleName] = $obj->ModuleName;
        }

        return $array;

    }
    /*
     * This function is just used to suppression of warnings
     * */
    private function catchFopenWarning($errno, $errstr)
    {
    }
}
