<?php

namespace Sunnysideup\ModuleChecks\Commands\FilesToAdd;

use Sunnysideup\ModuleChecks\Commands\FilesToAddAbstract;
use Sunnysideup\ModuleChecks\Model\Module;

class AddTestToModule extends FilesToAddAbstract
{
    protected $repoReplaceArray = [
        'Module' => 'ShortUCFirstName',
    ];

    protected $sourceLocation = 'app/template_files/ChecksAbstract.php';

    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function __construct(?Module $repo = null)
    {
        parent::__construct($repo);
        if($this->repo) {
            $this->fileLocation = 'tests/' . $this->repo->ShortUCFirstName() . 'Test.php';
        }
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Add example test file';
    }
}
