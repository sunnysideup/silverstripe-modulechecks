<?php

class RemoveAPI extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'rm ./docs/api/ -Rf'
    ];
}
