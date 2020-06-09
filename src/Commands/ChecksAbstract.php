<?php

namespace Sunnysideup\ModuleChecks\Commands;

abstract class ChecksAbstract extends BaseCommand
{
    private static $enabled = false;

    abstract public function run(): bool;

    abstract public function getDescription(): string;

    public function getError(): string
    {
        return 'Check returns with error';
    }

    protected function hasFileOnGitHub(string $file): bool
    {
        $name = $this->getName();
        $gitHubUserName = $this->Config()->get('github_user_name');

        return GeneralMethods::check_location(
            'https://raw.githubusercontent.com/' .
            $gitHubUserName . '/silverstripe-' . $name .
            '/master/' . $file
        );
    }
}
