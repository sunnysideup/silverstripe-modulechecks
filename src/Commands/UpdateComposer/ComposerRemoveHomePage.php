<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

class ComposerRemoveHomePage extends UpdateComposerAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run()
    {
        $json = $this->composerJsonObj->getJsonData();
        if (isset($json['authors'][0]['homepage'])) {
            unset($json['authors'][0]['homepage']);
        }
        if (isset($json['homepage'])) {
            unset($json['homepage']);
        }

        return $this->composerJsonObj->setJsonData($json);
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Remove composer requirements for dev-master versions.';
    }
}
