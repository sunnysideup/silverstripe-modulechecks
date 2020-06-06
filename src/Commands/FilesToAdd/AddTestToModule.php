<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\AddFileToModule;

class AddTestToModule extends AddFileToModule
{
    protected $gitReplaceArray = [
        'Module' => 'ShortUCFirstName',
    ];

    protected $sourceLocation = 'app/template_files/ModuleTest.php';

    public function __construct($gitObject)
    {
        $this-> fileLocation = 'tests/' . $gitObject->ShortUCFirstName() . 'Test.php';
        parent::__construct($gitObject);
    }
}
