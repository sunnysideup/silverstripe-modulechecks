<?php

namespace Sunnysideup\ModuleChecks\FilesToAdd;


use Sunnysideup\ModuleChecks\Api\AddFileToModule;


class AddUserguideMdToModule extends AddFileToModule
{
    protected $sourceLocation = 'app/template_files/docs/en/userguide.md';

    protected $fileLocation = 'docs/en/userguide.md';
}
