<?php

namespace Sunnysideup\ModuleChecks\Model;

use ReflectionClass;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;


use SilverStripe\ORM\DataObject;

use SilverStripe\ORM\Filters\ExactMatchFilter;


use SilverStripe\ORM\Filters\PartialMatchFilter;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;

use Sunnysideup\ModuleChecks\BaseObject;

class Check extends DataObject
{
    #######################
    ### Names Section
    #######################

    private static $singular_name = 'Check';

    private static $plural_name = 'Checks';

    private static $table_name = 'Check';

    #######################
    ### Model Section
    #######################

    private static $db = [
        'Title' => 'Varchar',
        'MyClass' => 'Varchar',
        'Enabled' => 'Boolean',
        'MustDo' => 'Boolean',
        'Type' => 'Varchar',
    ];

    private static $has_many = [
        'ModuleChecks' => ModuleCheck::class,
    ];

    private static $many_many = [
        'ExcludedFromPlan' => CheckPlan::class,
    ];

    private static $belongs_many_many = [
        'IncludedInPlan' => CheckPlan::class,
    ];

    #######################
    ### Further DB Field Details
    #######################

    private static $indexes = [
        'Title' => 'unique("Title")',
        'MyClass' => 'unique("MyClass")',
        'Enabled' => true,
        'MustDo' => true,
    ];

    private static $default_sort = [
        'ID' => 'ASC',
        'Type' => 'DESC',
        'Enabled' => 'DESC',
    ];

    private static $searchable_fields = [
        'MustDo' => ExactMatchFilter::class,
        'Enabled' => ExactMatchFilter::class,
        'Title' => PartialMatchFilter::class,
        'Type' => PartialMatchFilter::class,
    ];

    #######################
    ### Field Names and Presentation Section
    #######################

    private static $summary_fields = [
        'Title' => 'Title',
        'Enabled.Nice' => 'Enabled',
        'MustDo.Nice' => 'Must Do',
        'Type' => 'Group',
        'ModuleChecks.Count' => 'Run Count',
        'ExcludedFromPlan.Count' => 'Exclude Count',
        'IncludedInPlan.Count' => 'Include Count',
    ];


    #######################
    ### Casting Section
    #######################


    #######################
    ### can Section
    #######################

    private static $primary_model_admin_class = ModuleCheckModelAdmin::class;

    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    public function canDelete($member = null, $context = [])
    {
        return false;
    }

    public function canEdit($member = null, $context = [])
    {
        return false;
    }

    #######################
    ### write Section
    #######################

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        //...
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();
        //...
    }

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        foreach (Config::inst()->get(BaseObject::class, 'core_classes') as $class) {
            if ( ! class_exists($class)) {
                user_error('Could not find the following class: '.$class);
            }
            $classes = ClassInfo::subclassesFor($class, false);
            foreach ($classes as $class) {
                $classObject = Injector::inst()->get($class);
                $filter = ['MyClass' => $class];
                $obj = DataObject::get_one(Check::class, $filter);
                if (! $obj) {
                    $obj = Check::create($filter);
                }
                $obj->Title = $classObject->getDescription();
                $obj->MyClass = $class;
                $obj->Type = $classObject->calculateType();
                $obj->Enabled = Config::inst()->get($class, 'enabled');
                $obj->MustDo = Config::inst()->get($class, 'must_do');
                $obj->write();
            }
        }
        //...
    }


    #######################
    ### Import / Export Section
    #######################

    #######################
    ### CMS Edit Section
    #######################

    public function getCMSFields()
    {
        return parent::getCMSFields();
    }
}
