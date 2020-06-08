<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddTestToModule extends FilesToAddAbstract
{
    protected $gitReplaceArray = [
        'Module' => 'ShortUCFirstName',
    ];

    protected $sourceLocation = 'app/template_files/ChecksAbstract.php';

    public function __construct($gitObject)
    {
        $this-> fileLocation = 'tests/' . $gitObject->ShortUCFirstName() . 'Test.php';
        parent::__construct($gitObject);
    }
}
