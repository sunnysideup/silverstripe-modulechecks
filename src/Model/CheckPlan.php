<?php

namespace Sunnysideup\ModuleChecks\Model;

use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\ORM\DataObject;

use SilverStripe\ORM\FieldType\BaseObject;
use SilverStripe\ORM\FieldType\DBField;

use SilverStripe\ORM\Filters\ExactMatchFilter;


use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;

class CheckPlan extends DataObject
{
    protected static $current_module_check = null;

    #######################
    ### Names Section
    #######################

    private static $singular_name = 'Module Check Plan';

    private static $plural_name = 'Module Check Plan';

    private static $table_name = 'GitHubCheck';

    #######################
    ### Model Section
    #######################

    private static $db = [
        'Completed' => 'Boolean',
        'AllModules' => 'Boolean',
        'AllChecks' => 'Boolean',
    ];

    private static $has_many = [
        'ModuleChecks' => ModuleCheck::class,
    ];

    private static $many_many = [
        'IncludeModules' => Module::class,
        'IncludeChecks' => ModuleCheck::class,
    ];

    private static $many_many_extraFields = [];

    private static $belongs_many_many = [
        'ExcludeModules' => Module::class,
        'ExcludeChecks' => ModuleCheck::class,
    ];

    #######################
    ### Further DB Field Details
    #######################

    private static $cascade_deletes = [
        ModuleCheck::class,
    ];

    private static $indexes = [
        'Completed' => true,
        'AllModules' => true,
        'AllChecks' => true,
    ];

    private static $defaults = [
        'AllChecks' => true,
        'AllModules' => true,
    ];

    private static $default_sort = [
        'ID' => 'DESC',
    ];

    private static $searchable_fields = [
        'Completed' => ExactMatchFilter::class,
    ];

    #######################
    ### Field Names and Presentation Section
    #######################

    private static $field_labels = [
        'AllChecks' => 'Include All Checks',
        'AllModules' => 'Include All Modules',
    ];

    private static $summary_fields = [
        'Created.Nice' => 'Created',
        'Completed.Nice' => 'Completed',
        'AllChecks.Nice' => 'All Checks',
        'AllModules.Nice' => 'All Modules',
        'ModuleCount' => 'Modules Count',
        'CheckCount' => 'Check Count',
        'ModuleChecks.Count' => 'Module Check Count',
    ];

    #######################
    ### Casting Section
    #######################

    private static $casting = [
        'Title' => 'Varchar',
        'ModuleCount' => 'Int',
        'CheckCount' => 'Int',
    ];

    #######################
    ### can Section
    #######################

    private static $primary_model_admin_class = ModuleCheckModelAdmin::class;

    public static function get_current_check_plan(): CheckPlan
    {
        return DataObject::get_one(CheckPlan::class, ['Completed' => 0]);
    }

    public static function set_current_module_check(ModuleCheck $moduleCheck)
    {
        self::$current_module_check = $moduleCheck;
    }

    public static function get_current_module_check(): ?ModuleCheck
    {
        self::$current_module_check = $moduleCheck;
    }

    public static function get_next_module_check(): ?ModuleCheck
    {
        $plan = self::get_current_check_plan();

        self::$current_module_check = $plan->ModuleChecks()->filter(['Running' => 0, 'Completed' => 0])->first();

        return self::$current_module_check;
    }

    public function getTitle()
    {
        return DBField::create_field('Varchar', 'FooBar To Be Completed');
    }

    public function getModuleCount()
    {
        return DBField::create_field('Int', 'FooBar To Be Completed');
    }

    public function getCheckCount()
    {
        return DBField::create_field('Int', 'FooBar To Be Completed');
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
        $this->createChecks();
        //...
    }

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        //...
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
        $fields = parent::getCMSFields();

        //...
        $obj = BaseObject::inst();

        $fields->addFieldToTab(
            'Root.Main',
            CheckboxSetField(
                'ExcludeModules',
                'Excluded Modules',
                $obj->getAvailableModulesForDropdown()
            )
        );
        $fields->addFieldToTab(
            'Root.Main',
            CheckboxSetField(
                'IncludedModules',
                'Included Modules',
                $obj->getAvailableModulesForDropdown()
            )
        );
        $fields->addFieldToTab(
            'Root.Main',
            CheckboxSetField(
                'ExcludeChecks',
                'Excluded Checks',
                $obj->getAvailableChecksForDropdown()
            )
        );
        $fields->addFieldToTab(
            'Root.Main',
            CheckboxSetField(
                'IncludedChecks',
                'Included Checks',
                $obj->getAvailableChecksForDropdown()
            )
        );

        return $fields;
    }

    protected function createChecks()
    {
        $obj = BaseObject::inst();
        if ($this->AllChecks) {
            $checks = $obj->getAvailableChecks();
            foreach ($this->ExcludeChecks() as $excludedCheck) {
                unset($checks[$excludedCheck->ID]);
            }
        } else {
            $checks = [];
            foreach ($this->IncludeChecks() as $includedCheck) {
                $checks[$includedCheck->ID] = $includedCheck;
            }
        }
        if ($this->AllModules) {
            $modules = $obj->getAvailableModules();
            foreach ($this->ExcludeModules() as $excludedModules) {
                unset($modules[$excludedModules->ID]);
            }
        } else {
            $modules = [];
            foreach ($this->IncludeModules() as $includedModule) {
                $modules[$includedModule->ID] = $includedModule;
            }
        }
        foreach (array_keys($modules) as $moduleID) {
            foreach (array_keys($checks) as $checkID) {
                $filter = [
                    'ModuleCheckPlanID' => $this->ID,
                    'Module' => $moduleID,
                    'Check' => $checkID,
                ];
                $obj = DataObject::get_one(ModuleCheck::class, $filter);
                if (! $obj) {
                    $obj = ModuleCheck::create($obj);
                    $obj->write();
                }
            }
        }
    }
}
