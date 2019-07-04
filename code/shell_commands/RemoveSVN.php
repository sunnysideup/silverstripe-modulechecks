<?php

class RemoveSVN extends RunCommandLineMethodOnModule
{
    protected $commands = [
        ' find ./ -type d -name ".svn" -exec rm  -Rf "{}" \;'
    ];
}
