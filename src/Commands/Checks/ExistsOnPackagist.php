<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class ExistsOnPackagist extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    /**
     * @return boolean
     */
    public function run(): bool
    {
        $name = $this->getName();
        $packagistUserName = $this->Config()->get('packagist_user_name');

        return GeneralMethods::check_location(
            'https://packagist.org/packages/' .
            $packagistUserName . '/' . $name
        );
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Does the module exist on packagist.org?';
    }
}
