<?php

namespace Sunnysideup\ModuleChecks\Model;





use Error;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\Filters\ExactMatchFilter;
use SilverStripe\ORM\Filters\PartialMatchFilter;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class ModuleCheck extends DataObject
{


    #######################
    ### Names Section
    #######################

    private static $singular_name = 'Module Check';

    private static $plural_name = 'Module Checks';

    private static $table_name = 'ModuleCheck';




    #######################
    ### Model Section
    #######################

    private static $db = [
        'Running' => 'Boolean',
        'Completed' => 'Boolean',
        'Error' => 'Varchar'
    ];

    private static $has_one = [
        'ModuleCheckPlan' => ModuleCheck::class,
        'GitHubModule' => GitHubModule::class,
        'Check' => Check::class
    ];


    #######################
    ### Further DB Field Details
    #######################

    private static $indexes = [
        'Completed' => true,
        'Created' => true,
        'Running' => true
    ];

    private static $default_sort = [
        'Running' => 'DESC',
        'Completed' => 'ASC',
        'Created' => 'ASC'
    ];

    private static $searchable_fields = [
        'GitHubModuleID' => ExactMatchFilter::class,
        'ModuleCheckPlanID' => ExactMatchFilter::class,
        'Running' => ExactMatchFilter::class,
        'Completed' => ExactMatchFilter::class,
    ];

    #######################
    ### Field Names and Presentation Section
    #######################

    private static $field_labels = [
        'ModuleCheckPlan' => 'Plan',
        'GitHubModule' => 'Module',
        'Running' => 'Started Running',
    ];

    private static $summary_fields = [
        'Created.Nice' => 'Created',
        'LastEdited.Nice' => 'Last Edited',
        'Running.Nice' => 'Running',
        'Completed.Nice' => 'Completed',
        'ModuleCheckPlan.Title' => 'Plan',
    ];


    #######################
    ### Casting Section
    #######################

    private static $casting = [
        'Title' => 'Varchar',
    ];

    public function getTitle()
    {
        return DBField::create_field('Varchar', 'FooBar To Be Completed');
    }



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

        return $fields;
    }


}
