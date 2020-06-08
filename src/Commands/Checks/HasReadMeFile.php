<?php

namespace Sunnysideup\ModuleChecks\Commands\Checks;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;
use Sunnysideup\ModuleChecks\Api\GeneralMethods;

class HasReadMeFile extends ChecksAbstract
{
    /**
     *
     * @return boolean
     */
    public function run() : bool
    {
        return $this->hasFileOnGitHub('README.md');
        $name = $this->getName();
        $gitHubUserName = $this->Config()->get('github_user_name');

        return GeneralMethods::check_location(
            'https://raw.githubusercontent.com/' .
            $gitHubUserName . '/silverstripe-' . $name .
            '/master/README.md'
        );
    }


}
