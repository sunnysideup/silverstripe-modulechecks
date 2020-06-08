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

class BaseObject
{

    use Extensible;
    use Injectable;
    use Configurable;

    private const CHECKS = [
        'core_classes',
        'github_account_base_url',
        'github_user_name',
        'github_user_email',
        'path_to_private_key',
        'absolute_temp_folder',
        'license_type',
    ];
    /**
     * list of classes to run,  in the right order
     * @var array
     */
    private static $core_classes = [
        'ChecksAbstract',
        'FilesToAddAbstract',
        'UpdateComposerAbstract',
        'ShellCommandsAbstract',
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

    private static $debug = true;

    /**
     *
     * @var string
     */
    private static $packagist_user_name = '';

    public function areWeReady()
    {
        foreach(self::CHECKS as $check) {
            $value = $this->Config()->get($check);
            if(! $value) {
                user_error('You need to set '.$check.' as a private static in BaseObject');
            }
            if(strpos($value, '/') !== false) {
                if(! file_exists($value)) {
                    user_error('The following dir/file can not be found! '.$value);
                }
            }
        }
    }


    public function availableCommands()
    {
        $list = [];
        foreach($this->Config()->get('core_classes') as $class) {
            $classes = ClassInfo::subclassesFor($class, false);
            foreach($classes as $class) {
                $list[$class] = [
                    'Name' => $class,
                    'Description' => Injector::inst()->get($class)->getDescription(),
                ];
            }
        }

        return $list;
    }

}
