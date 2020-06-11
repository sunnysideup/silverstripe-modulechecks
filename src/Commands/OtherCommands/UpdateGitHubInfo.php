<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Commands\ChecksAbstract;

class UpdateGitHubInfo extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run(): bool
    {
        $array = [];
        // see https://developer.github.com/v3/repos/#edit

        # not working
        // $composerJsonObj->readJsonFile();
        // $moduleObject->setDescription($composerJsonObj->getDescription());
        // if (! $this->composerJsonObj->getJsonData()) {
        //     user_error('No Json data!');
        // }
        $defaultValues = [
            'name' => $this->repo->LongModuleName(),
            'has_wiki' => false,
            'has_issues' => true,
            'has_downloads' => true,
            'homepage' => Config::inst()->get(BaseObject::class, 'home_page'),
            //CHECK IF THIS IS RIGHT FIELD ...
            'description' => Config::inst()->get(BaseObject::class, 'home_page'),
        ];

        if ($this->repo->Description) {
            $array['description'] = $this->repo->Description;
        }

        foreach ($defaultValues as $key => $value) {
            if (! isset($array[$key])) {
                $array[$key] = $value;
            }
        }

        FlushNow::flushNow('updating Git Repo information ...');

        //check!
        $obj = GitHubApi::create();
        $obj->apiCall($this->repo->ModuleName, $array, '', 'PATCH');

        return $this->hasError();
    }

    public function getDescription(): string
    {
        return 'Update Git Hub Info';
    }
}
