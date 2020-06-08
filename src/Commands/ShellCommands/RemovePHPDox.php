<?php

class RemovePHPDox extends RunCommandLineMethodOnModule
{
    protected $command = [
        'rm ./docs/en/phpdox/ -rf',
        'rm ./docs/phpdox/ -rf',
        'rm ./docs/phpdox/ -rf',
        'rm ./docs/api/ -rf',
        'rm ./docs/build/ -rf ',
    ];
}
