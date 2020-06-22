<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;

use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\Flush\FlushNow;
use SilverStripe\Core\Config\Config;
use Sunnysideup\ModuleChecks\BaseObject;

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
        $name = $this->getNameWithoutSilverstripe();
        $packagistUserName = Config::inst()->get(BaseObject::class, 'packagist_user_name');
        $location = 'https://packagist.org/packages/' . $packagistUserName . '/' . $name;

        return $this->checkLocation($location);
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
