<?php

class AddTestToModule extends AddFileToModule
{
	
	
	protected $gitReplaceArray = array(
        'Module' => 'LongModuleName',
    );

    protected $sourceLocation = 'source/ModuleTest.php';

    protected $fileLocation = 'tests/ModuleTest.php';
}
