<?php

namespace Sunnysideup\ModuleChecks\Commands;
use Sunnysideup\ModuleChecks\Commands\BaseCommand;

abstract class ChecksAbstract extends BaseCommand
{
    private static $enabled = false;

    abstract public function run(): bool;

    abstract public function getDescription(): string;

    public function getError(): string
    {
        return 'Check returns with error';
    }

    /**
     * opens a location with curl to see if it exists.
     *
     * @param string $url
     *
     * @return boolean
     */
    public function checkLocation(string $url): bool
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, true);
        curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $outcome = $httpCode === intval(200) ? true : false;
        curl_close($handle);

        return $outcome;
    }

    protected function hasFileOnGitHub(string $file): bool
    {
        $name = $this->getName();
        $gitHubUserName = $this->Config()->get('github_user_name');

        return $this->checkLocation(
            'https://raw.githubusercontent.com/' .
            $gitHubUserName . '/silverstripe-' . $name .
            '/master/' . $file
        );
    }
}
