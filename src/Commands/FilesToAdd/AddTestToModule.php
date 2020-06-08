<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;

class AddTestToModule extends FilesToAddAbstract
{
    protected $gitReplaceArray = [
        'Module' => 'ShortUCFirstName',
    ];

    protected $sourceLocation = 'app/template_files/ChecksAbstract.php';

    public function __construct($repo)
    {
        parent::__construct($repo);
        $this->fileLocation = 'tests/' . $repo->ShortUCFirstName() . 'Test.php';
    }

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * what does it do?
     * @return string
     */
    public function getDescription() : string
    {
        return 'Add example test file';
    }
}
