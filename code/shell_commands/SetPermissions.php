<?php

class SetPermissions extends RunCommandLineMethodOnModule
{
    protected $command = 'find ./ -type f -exec chmod 644 {} \; find . -type d -exec chmod 755 {} \;';
}
