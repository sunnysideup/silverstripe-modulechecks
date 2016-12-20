<?php
class Phpdox extends RunCommandLineMethodOnModule
{
    public function __construct($rootDirForModule = '')
    {
        parent::__construct($rootDirForModule);

        $this->command = 'phpdox ' . $rootDirForModule . '/docs/en/phpdox/phpdox.xml';
    }
}
