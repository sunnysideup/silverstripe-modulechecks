<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

class ComposerRemoveDevMaster extends UpdateComposerAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = false;

    public function run()
    {
        $json = $this->composerJsonObj->getJsonData();
        $require = $json['require'];
        foreach ($require as $requirement => $value) {
            $isDevMaster = preg_match('/dev.?master/', strtolower($value)) === 1;
            if ($isDevMaster) {
                $json['require'][$requirement] = '*';
            }
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
