<?php

class RemoveOrig extends RunCommandLineMethodOnModule
{
    protected $command = ' find . -type f -name "*.orig" -exec rm  -Rf "{}" \;';
}
