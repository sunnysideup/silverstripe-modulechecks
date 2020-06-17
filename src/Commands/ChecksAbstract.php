<?php

namespace Sunnysideup\ModuleChecks\Commands;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\Api\FileMethods;

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
        return FileMethods::check_location_exists($url);
    }

    protected function hasFileOnGitHub(string $fileName): bool
    {
        if($this->repo) {
            return GitHubApi::has_file_on_git_hub($this->repo, $fileName);
        } else {
            $this->logError("Please provide repo.");

            return false;
        }

    }
}
