<?php

namespace Sunnysideup\ModuleChecks\Model;

use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\ORM\DataObject;

use SilverStripe\ORM\FieldType\DBField;

use SilverStripe\ORM\Filters\ExactMatchFilter;

use Sunnysideup\ModuleChecks\BaseObject;

use Sunnysideup\ModuleChecks\Admin\ModuleCheckModelAdmin;

use Sunnysideup\Flush\FlushNow;
use Sunnysideup\CMSNiceties\Forms\CMSNicetiesLinkButton;

class CheckPlan extends DataObject
{

    use FlushNow;

    protected static $current_module_check = null;

    protected $availableChecksList = [];

    protected $availableRepos = [];

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
        'IncludeChecks' => Check::class,
    ];

    private static $many_many_extraFields = [];

    private static $belongs_many_many = [
        'ExcludeModules' => Module::class,
        'ExcludeChecks' => Check::class,
    ];

    #######################
    ### Further DB Field Details
    #######################

    private static $cascade_deletes = [
        'ModuleChecks',
    ];

    private static $indexes = [
        'Completed' => true,
        'AllModules' => false,
        'AllChecks' => false,
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

    public static function get_current_check_plan(?int $id = 0): CheckPlan
    {
        if($id) {
            $obj = CheckPlan::get()->byID($id);
            if($obj) {
                return $obj;
            }
        }
        return DataObject::get_one(CheckPlan::class, ['Completed' => 0]);
    }

    public static function set_current_module_check(ModuleCheck $moduleCheck)
    {
        self::$current_module_check = $moduleCheck;
    }

    public static function get_current_module_check(): ?ModuleCheck
    {
        return self::$current_module_check;
    }

    public static function get_next_module_check(?int $checkPlanID = 0, ?int $moduleCheckID = 0): ?ModuleCheck
    {
        $plan = self::get_current_check_plan($checkPlanID);
        self::$current_module_check = null;
        if($moduleCheckID) {
            self::$current_module_check = $plan->ModuleChecks()->byID($moduleCheckID);
        }
        if(! self::$current_module_check) {
            self::$current_module_check = $plan->ModuleChecks()->filter(['Running' => 0, 'Completed' => 0])->first();
        }

        return self::$current_module_check;
    }

    public function getTitle()
    {
        return DBField::create_field('Varchar', $this->ModuleChecks()->count() . ' Checks');
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


    #######################
    ### CMS Edit Section
    #######################

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('ExcludeModules');
        $fields->removeByName('IncludeModules');
        $fields->removeByName('IncludeChecks');
        $fields->removeByName('ExcludeChecks');
        //...
        $obj = BaseObject::inst();

        $fields->addFieldsToTab(
            'Root.Modules',
            [
                CheckboxField::create('AllModules'),
                $exMods = CheckboxSetField::create(
                    'ExcludeModules',
                    'Excluded Modules',
                    $this->getAvailableModulesForDropdown()
                ),
                $incMods = CheckboxSetField::create(
                    'IncludeModules',
                    'Included Modules',
                    $this->getAvailableModulesForDropdown()
                ),

            ]
        );
        $exMods->displayIf("AllModules")->isChecked();
        $incMods->displayIf("AllModules")->isNotChecked();

        $fields->addFieldsToTab(
            'Root.Checks',
            [
                CheckboxField::create('AllChecks'),
                $exChecks = CheckboxSetField::create(
                    'ExcludeChecks',
                    'Excluded Checks',
                    $this->getAvailableChecksForDropdown()
                ),
                $incChecks = CheckboxSetField::create(
                    'IncludeChecks',
                    'Included Checks',
                    $this->getAvailableChecksForDropdown()
                ),
            ]
        );
        $exChecks->displayIf("AllChecks")->isChecked();
        $incChecks->displayIf("AllChecks")->isNotChecked();

        $fields->addFieldsToTab(
            'Root.Actions',
            [
                CMSNicetiesLinkButton::create(
                    'LoadModules',
                    'load modules',
                    '/dev/tasks/load-modules'
                ),
                CMSNicetiesLinkButton::create(
                    'DisableModules',
                    'disable non-applicable modules',
                    '<h2 style="text-align: left"><a href="/dev/tasks/disable-modules-based-on-composer-type"></a></h2>'
                ),
                LiteralField::create(
                    'CreateCheckPlan',
                    '<h2 style="text-align: left"><a href="/dev/tasks/create-check-plan?id='.$this->ID.'">create check plan</a></h2>'
                ),
                LiteralField::create(
                    'RunCheckPlan',
                    '<h2 style="text-align: left"><a href="/dev/tasks/run-check-plan/?id='.$this->ID.'">run check plan</a></h2>'
                ),
            ]
        );


        return $fields;
    }

    public function createChecks($debug = false)
    {
        if ($this->AllChecks) {
            $checks = $this->getAvailableChecks();
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
            $modules = $this->getAvailableModules();
            foreach ($this->ExcludeModules() as $excludedModules) {
                unset($modules[$excludedModules->ID]);
            }
        } else {
            $modules = [];
            foreach ($this->IncludeModules() as $includedModule) {
                $modules[$includedModule->ID] = $includedModule;
            }
        }
        foreach ($modules as $module) {
            foreach ($checks as $check) {
                $filter = [
                    'CheckPlanID' => $this->ID,
                    'ModuleID' => $module->ID,
                    'CheckID' => $check->ID,
                ];
                if($debug) {
                    $this->flushNow('<pre>'.  print_r($filter, 1) . '</pre><hr />');
                }
                $obj = ModuleCheck::get()->filter($filter)->first();
                if (! $obj) {
                    $obj = ModuleCheck::create($obj);
                }
                foreach($filter as $field => $value) {
                    $obj->{$field} = $value;
                }
                $obj->write();
            }
        }
    }

    protected function mustDoChecks()
    {

    }


    protected function getAvailableChecks(): array
    {
        if (! count($this->availableChecksList)) {
            $list = Check::get();
            foreach ($list as $obj) {
                if ($obj->Enabled) {
                    $this->availableChecksList[$obj->MyClass] = $obj;
                }
            }
        }

        return $this->availableChecksList;
    }

    protected function getAvailableChecksForDropdown(): array
    {
        $list = $this->getAvailableChecks();
        $array = [];
        foreach ($list as $obj) {
            $array[$obj->ID] = $obj->Type . ': ' . $obj->Title;
        }
        return $array;
    }

    protected function getAvailableModules(): array
    {
        if (! count($this->availableRepos)) {
            $list = Module::get();
            foreach ($list as $obj) {
                if (! $obj->Disabled) {
                    $this->availableRepos[$obj->ModuleName] = $obj;
                }
            }
        }

        return $this->availableRepos;
    }

    protected function getAvailableModulesForDropdown(): array
    {
        $list = $this->getAvailableModules();
        $array = [];
        foreach ($list as $obj) {
            $array[$obj->ID] = $obj->ModuleName;
        }
        asort($array);

        return $array;
    }

}
