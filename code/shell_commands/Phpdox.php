<?php
class Phpdox extends RunCommandLineMethodOnModule
{
    public function __construct($rootDirForModule = '')
    {
        parent::__construct();

        $this->command = 'phpdox ' . $rootDirForModule . '/docs/en/phpdox/phpdox.xml';
    }
}
