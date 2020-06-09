<?php

namespace Sunnysideup\ModuleChecks\Model;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;


use SilverStripe\ORM\Filters\ExactMatchFilter;

use SilverStripe\ORM\Filters\PartialMatchFilter;


use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;

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
    ];

    private static $default_sort = [
        'Enabled' => 'DESC',
        'Type' => 'DESC',
        'ID' => 'ASC',
    ];

    private static $searchable_fields = [
        'Enabled' => ExactMatchFilter::class,
        'Title' => PartialMatchFilter::class,
        'Type' => PartialMatchFilter::class,
    ];

    #######################
    ### Field Names and Presentation Section
    #######################


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
            $classes = ClassInfo::subclassesFor($class, false);
            foreach ($classes as $class) {
                $filter = ['MyClass' => $class];
                $obj = DataObject::get_one($class, $filter);
                if (! $obj) {
                    $obj = $class::create($filter);
                }
                $obj->Title = $obj->getDescription();
                $obj->MyClass = $class;
                $obj->Type = $this->calculateType();
                $obj->Enabled = Config::inst()->get($class, 'enabled');
                $obj->write();
            }
        }
        //...
    }

    public function calculateType(): string
    {
        $list = class_parents($this);
        foreach ($list as $class) {
            $abstractClass = new ReflectionClass($class);
            if ($abstractClass->isAbstract()) {
                return ClassInfo::shortName($class);
            }
        }

        return 'error';
    }

    #######################
    ### Import / Export Section
    #######################

    public function getExportFields()
    {
        //..
        return parent::getExportFields();
    }

    #######################
    ### CMS Edit Section
    #######################

    public function getCMSFields()
    {
        return parent::getCMSFields();
    }
}
