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


class GitHubModule extends DataObject
{


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
