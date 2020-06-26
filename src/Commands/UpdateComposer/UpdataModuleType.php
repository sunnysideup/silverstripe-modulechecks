<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

/**
 * sets the default installation folder
 */
class UpdataModuleType extends UpdateComposerAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = false;

    public function run(): bool
    {
        $json = $this->getJsonData();
        if($json['type'] === 'silverstripe-module') {
            $json['type'] = 'silverstripe-vendormodule';
        }

        $this->setJsonData($json);
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Change from silverstripe-module to silverstripe-vendormodule.';
    }
}
