<?php

class RemoveOldReadMe extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'rm docs/en/README.old.md -rf'
    ];
}
