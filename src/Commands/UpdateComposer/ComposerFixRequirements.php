<?php

namespace Sunnysideup\ModuleChecks\Commands\UpdateComposer;

use Sunnysideup\ModuleChecks\Commands\UpdateComposerAbstract;

class ComposerFixRequirements extends UpdateComposerAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = false;

    public function run(): bool
    {
        $json = $this->composerJsonObj->getJsonData();
        $require = $json['require'];

        if (isset($require['silverstripe/framework'])) {
            $is2Point4 = strpos($require['silverstripe/framework'], '2.4') !== false;
        } else {
            $is2Point4 = false;
        }

        if ($is2Point4) {
            $version = '~2.4';
        } else {
            $version = '~3.6';
        }

        $json['require']['silverstripe/framework'] = $version;
        $json['require']['silverstripe/cms'] = $version;

        $this->composerJsonObj->setJsonData($json);
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Set framework version.';
    }
}
