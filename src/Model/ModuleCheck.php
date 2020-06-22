<?php

namespace Sunnysideup\ModuleChecks\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\Filters\ExactMatchFilter;
use SilverStripe\ORM\Filters\PartialMatchFilter;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ReadonlyField;
use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;
use Sunnysideup\Flush\FlushNow;
use Sunnysideup\CMSNiceties\Forms\CMSNicetiesLinkButton;
use Sunnysideup\CMSNiceties\Traits\CMSNicetiesTraitForCMSLinks;

class ModuleCheck extends DataObject
{
    use FlushNow;
    use CMSNicetiesTraitForCMSLinks;

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
        'CheckPlan' => CheckPlan::class,
        'Module' => Module::class,
        'Check' => Check::class,
    ];

    #######################
    ### Further DB Field Details
    #######################

    private static $indexes = [
        'Running' => true,
        'Completed' => true,
    ];

    private static $default_sort = [
        'ID' => 'ASC',
    ];

    private static $searchable_fields = [
        'Module.ModuleName' => PartialMatchFilter::class,
        'Check.Title' => PartialMatchFilter::class,
        'CheckPlanID' => ExactMatchFilter::class,
        'Running' => ExactMatchFilter::class,
        'HasError' => ExactMatchFilter::class,
        'Completed' => ExactMatchFilter::class,
    ];

    #######################
    ### Field Names and Presentation Section
    #######################

    private static $field_labels = [
        'CheckPlan' => 'Plan',
        'Module' => 'Module',
        'Running' => 'Started Running',
    ];

    private static $summary_fields = [
        'Created.Nice' => 'Created',
        'LastEdited.Nice' => 'Last Edited',
        'Running.Nice' => 'Running',
        'Completed.Nice' => 'Completed',
        'HasError.Nice' => 'Error',
        'CheckPlan.Title' => 'Plan',
        'Check.Title' => 'Check',
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
        $fields = parent::getCMSFields();
        if($this->canBeRun()) {
            $fields->addFieldToTab(
                'Root.Main',
                CMSNicetiesLinkButton::create(
                    'RunNow',
                    'Run this check',
                    'dev/tasks/run-check-plan/?modulecheckid='.$this->ID
                )
            );
        } else {
            $fields->addFieldToTab(
                'Root.Main',
                ReadonlyField::create(
                    'RunNow',
                    '',
                    'This task can not be run (already started / completed)'
                )
            );
        }
        $fields->replaceField(
            'ModuleID',
            $this->CMSEditLinkField('Module', 'Module')
        );

        return $fields;

    }

    #######################
    ### Running it
    #######################

    public function canBeRun() : bool
    {
        return $this->Completed || $this->Running ? false : true;
    }
    public function run()
    {
        $this->Running = true;
        $this->write();
        $check = $this->Check();
        if ($check && $check->exists()) {
            $repo = $this->Module();
            if ($repo && $repo->exists()) {
                $commandClassName = $check->MyClass;
                $commandObject = new $commandClassName($repo);
                $outcome = $commandObject->run();
                $this->Running = false;
                $this->Completed = true;
                if ($outcome) {
                    $this->write();
                } else {
                    $this->logError($commandObject->getError());
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

    public static function log_error(string $message)
    {
        FlushNow::do_flush($message, 'deleted');
        $obj = CheckPlan::get_current_module_check();
        if ($obj) {
            $obj->LogError($message);
        } else {
            FlushNow::do_flush('Could not attach error to specific ModuleCheck');
        }
    }
}
