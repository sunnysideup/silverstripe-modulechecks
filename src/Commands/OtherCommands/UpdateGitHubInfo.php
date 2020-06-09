<?php

namespace Sunnysideup\ModuleChecks\Commands\OtherCommands;

use Sunnysideup\ModuleChecks\Api\GeneralMethods;
use Sunnysideup\ModuleChecks\Api\GitHubApi;
use Sunnysideup\ModuleChecks\BaseObject;

class UpdateGitHubInfo extends ChecksAbstract
{
    /**
     * should it be included by default?
     * @var bool
     */
    private static $enabled = true;

    public function run($array): bool
    {
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

        GeneralMethods::output_to_screen('updating Git Repo information ...');

        $obj = new GitHubApi();
        $obj->apiCall($this->repo->ModuleName, $array, $gitAPIcommand, 'PATCH');

        return true;
    }
}
