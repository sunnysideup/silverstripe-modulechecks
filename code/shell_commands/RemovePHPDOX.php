<?php

class RemovePHPDOX extends RunCommandLineMethodOnModule
{
    protected $command = 'rm ./docs/api/phpdox/ -Rf';
}
