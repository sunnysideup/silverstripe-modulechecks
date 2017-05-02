<?php

class RemoveSVN extends RunCommandLineMethodOnModule
{
    protected $command = ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;';
}
