<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\Api\Scrutinizer;
use Sunnysideup\ModuleChecks\BaseObject;

class AddToScrutinizer extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run(): bool
    {
        Scrutinizer::send_to_scrutinizer(
            Config::inst()->get(BaseObject::class, 'scrutinizer_api_key'),
            Config::inst()->get(BaseObject::class, 'github_user_name'),
            $this->repo->ModuleName
        );

        return true;
    }

    public function getDescription()
    {
        return 'Add to Scrutinizer';
    }
}
