<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;

use AddFileToModule;

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
