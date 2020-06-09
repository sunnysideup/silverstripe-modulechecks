<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

/**
 * sets the default installation folder
 */
class CheckOrAddExtraArray extends UpdateComposerAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = false;

    public function run(): bool
    {
        $json = $this->getJsonData();

        if (isset($json['extra'])) {
            GeneralMethods::output_to_screen('<li> already has composer.json[extra][installer-name] </li>');

            return false;
        }
        GeneralMethods::output_to_screen("<li> Adding 'extra' array to composer.json </li>");
        if (! isset($json['extra'])) {
            $json['extra'] = [];
        }
        $json['extra']['installer-name'] = str_replace('silverstripe-', '', $this->composerJsonObj->moduleName);

        $this->setJsonData($json);

        return true;
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Fix extra installer folder.';
    }
}
