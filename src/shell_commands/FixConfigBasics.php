<?php

class FixConfigBasics extends RunCommandLineMethodOnModule
{
    protected $commands = [
        'mv ./config ./_config'
    ];
}

