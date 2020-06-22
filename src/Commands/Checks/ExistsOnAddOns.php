<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\BaseObject;
use SilverStripe\Core\Config\Config;

class ExistsOnAddOns extends ChecksAbstract
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
        $name = $this->getNameWithoutSilverstripe();
        $packagistUserName = Config::inst()->get(BaseObject::class, 'packagist_user_name');

        return $this->checkLocation(
            'https://addons.silverstripe.org/add-ons/' .
            $packagistUserName .
            '/' . $name
        );
    }

    /**
     * what does it do?
     * @return string
     */
    public function getDescription(): string
    {
        return 'Does the module exist on addons.silverstripe.org?';
    }
}
