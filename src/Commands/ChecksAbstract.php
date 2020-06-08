<?php

namespace Sunnysideup\ModuleChecks\Commands;

abstract class ChecksAbstract extends BaseObject
{

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    protected $repo = null;

    abstract public function run() : bool;

    protected function getName() :string
    {
        return $this->repo->ModuleName;
    }

    protected function hasFileOnGitHub(string $file) : bool
    {
        $name = $this->getName();
        $gitHubUserName = $this->Config()->get('github_user_name');

        return GeneralMethods::check_location(
            'https://raw.githubusercontent.com/' .
            $gitHubUserName . '/silverstripe-' . $name .
            '/master/'.$file
        );
    }

    // abstract public function description() : string;

}
