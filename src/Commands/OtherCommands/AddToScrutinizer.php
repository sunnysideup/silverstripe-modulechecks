<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\Api\Scrutinizer;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use SilverStripe\Core\Config\Config;

class AddToScrutinizer extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run(): bool
    {
        $apiKey = Config::inst()->get(BaseObject::class, 'scrutinizer_api_key');
        $gitHubUserName = Config::inst()->get(BaseObject::class, 'github_user_name');
        $outcome = null;
        if ($apiKey) {
            $outcome = Scrutinizer::send_to_scrutinizer(
                $apiKey,
                $gitHubUserName,
                $this->repo->ModuleName
            );
            print_r($outcome);
        } else {
            $this->logError('API Key Not Set');
        }
        if(! $outcome) {
            $this->logError('Could not add to scrutinizer');
        }
        return $this->hasError();
    }

    public function getDescription(): string
    {
        return 'Add to Scrutinizer';
    }
}
