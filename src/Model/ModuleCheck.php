<?php

namespace Sunnysideup\ModuleChecks\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\Filters\ExactMatchFilter;
use SilverStripe\ORM\Filters\PartialMatchFilter;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;

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
        'HasError' => 'Boolean',
        'Error' => 'Text',
    ];

    private static $has_one = [
        'ModuleCheckPlan' => ModuleCheck::class,
        'Module' => Module::class,
        'Check' => Check::class,
    ];

    #######################
    ### Further DB Field Details
    #######################

    private static $indexes = [
        'Completed' => true,
        'Running' => true,
    ];

    private static $default_sort = [
        'Running' => 'DESC',
        'Completed' => 'ASC',
        'ID' => 'ASC',
    ];

    private static $searchable_fields = [
        'Module.ModuleName' => PartialMatchFilter::class,
        'Check.Title' => PartialMatchFilter::class,
        'ModuleCheckPlanID' => ExactMatchFilter::class,
        'Running' => ExactMatchFilter::class,
        'HasError' => ExactMatchFilter::class,
        'Completed' => ExactMatchFilter::class,
    ];

    #######################
    ### Field Names and Presentation Section
    #######################

    private static $field_labels = [
        'ModuleCheckPlan' => 'Plan',
        'Module' => 'Module',
        'Running' => 'Started Running',
    ];

    private static $summary_fields = [
        'Created.Nice' => 'Created',
        'LastEdited.Nice' => 'Last Edited',
        'Running.Nice' => 'Running',
        'Completed.Nice' => 'Completed',
        'HasError.Nice' => 'Error',
        'ModuleCheckPlan.Title' => 'Plan',
        'Module.Title' => 'Module',
        'Module.Title' => 'Module',
    ];

    #######################
    ### Casting Section
    #######################

    private static $casting = [
        'Title' => 'Varchar',
    ];

    #######################
    ### can Section
    #######################

    private static $primary_model_admin_class = ModuleCheckModelAdmin::class;

    public function getTitle()
    {
        return DBField::create_field('Varchar', 'FooBar To Be Completed');
    }

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

    #######################
    ### Running it
    #######################

    public function run()
    {
        $this->Running = true;
        $this->write();
        $check = $this->Check();
        if ($check && $check->exists()) {
            $repo = $this->GitHubModule();
            if ($repo && $repo->exists()) {
                $commandClassName = $check->MyClass;
                $commandClassName::create($repo);
                $outcome = $commandClassName->run();
                if ($outcome) {
                    $this->Running = false;
                    $this->Completed = true;
                    $this->write();
                } else {
                    $this->Running = false;
                    $this->logError($commandClassName->getError());
                }
            } else {
                $this->LogError('Module ID #' . $this->ModuleID . ' can not be found.');
            }
        } else {
            $this->LogError('Check ID #' . $this->CheckID . ' can not be found.');
        }
    }

    public function LogError($message)
    {
        $this->Error .= '
        | ' . $message;
        $this->HasError = true;
        $this->write();
    }
}
