<?php

class AddTravisYmlToModule extends AddFileToModule
{
    protected $sourceLocation = 'mysite/template_files/.travis.yml';

    protected $fileLocation = '.travis.yml';
}
