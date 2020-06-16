<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;
use Sunnysideup\Flush\FlushNow;

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
            FlushNow::do_flush('Already has composer.json[extra][installer-name]');

            return false;
        }
        FlushNow::do_flush("Adding 'extra' array to composer.json");
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
