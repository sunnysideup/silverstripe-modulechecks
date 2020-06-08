<?php

namespace Sunnysideup\ModuleChecks\Model;

use Exception;
use SilverStripe\Assets\Filesystem;

use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\Exception\GitException;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataObject;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;

use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\BaseObject;

use SilverStripe\ORM\Filters\ExactMatchFilter;

use SilverStripe\Security\Member;

use SilverStripe\Security\Permission;
use SilverStripe\Forms\CheckboxSetField;




class CheckPlan extends DataObject
{

    public static function get_current_check_plan() : CheckPlan
    {
        return DataObject::get_one(CheckPlan::class, ['Completed' => 0]);
    }

    public static function get_next_module_check() : ?ModuleCheck
    {
        $plan = self::get_current_check_plan();

        return $plan->ModuleChecks()->filter(['Running' => 0, 'Completed' => 0])->first();
    }

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
        'IncludeModules' => GitHubModule::class,
        'IncludeChecks' => ModuleCheck::class,
    ];

    private static $many_many_extraFields = [];

    private static $belongs_many_many = [
        'ExcludeModules' => GitHubModule::class,
        'ExcludeChecks' => ModuleCheck::class,
    ];


    #######################
    ### Further DB Field Details
    #######################




    private static $cascade_deletes = [
        ModuleCheck::class
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
        'AllModules' => 'Include All Modules'
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



    #######################
    ### can Section
    #######################


    private static $primary_model_admin_class = ModuleCheckModelAdmin::class;



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
                $obj->getAvailableReposForDropdown()
            )
        );
        $fields->addFieldToTab(
            'Root.Main',
            CheckboxSetField(
                'IncludedModules',
                'Included Modules',
                $obj->getAvailableReposForDropdown()
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

    }

}
