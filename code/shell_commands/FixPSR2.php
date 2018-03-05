<?php
class FixPSR2 extends RunCommandLineMethodOnModule
{
    public function __construct($rootDirForModule = '')
    {
        parent::__construct($rootDirForModule);
        $folder = Director::baseFolder(). '/modulechecks/vendor/friendsofphp/php-cs-fixer/';
        // $this->command = 'php '.$folder.'php-cs-fixer fix .  --level=psr2';
        $this->command = 'php php-cs-fixer fix ./  --rules=@PSR2';
    }
}
