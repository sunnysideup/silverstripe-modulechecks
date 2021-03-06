<?php

namespace Sunnysideup\ModuleChecks\Api;

use Sunnysideup\ModuleChecks\BaseObject;
use Sunnysideup\ModuleChecks\Tasks\UpdateModules;
use Sunnysideup\Flush\FlushNow;

class Scrutinizer extends BaseObject
{
    /**
     *
     * @param  string $apiKey         [description]
     * @param  string $gitHubUserName [description]
     * @param  string $moduleName     [description]
     * @return bool                   [description]
     */
    public static function send_to_scrutinizer(string $apiKey, string $gitHubUserName, string $moduleName): bool
    {
        if (! trim($apiKey)) {
            FlushNow::do_flush('No Scrutinizer API key set');
            return false;
        }

        //see https://scrutinizer-ci.com/docs/api/#repositories
        $scrutinizerApiPath = 'https://scrutinizer-ci.com/api';
        $endPoint = 'repositories/g?access_token=' . trim($apiKey);
        $url = $scrutinizerApiPath . '/' . $endPoint;
        $repoName = $gitHubUserName . '/' . $moduleName;

        $postFields = [
            'name' => $repoName,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $curlResult = curl_exec($ch);

        if (! $curlResult) {
            ModuleCheck::log_error("Could not add ${repoName} to Scrutiniser (curl failure)");

            return false;
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode === 201) {
            FlushNow::do_flush('Added ' . $repoName . ' to Scrutinizer ... ');

            return true;
        }
        //UpdateModules::$unsolvedItems[$repoName] = "Could not add $reopName to Scrutiniser (HttpCode $httpcode)";
        ModuleCheck::log_error("Could not add ${repoName} to Scrutiniser (HttpCode ${httpcode})");

        return false;
    }
}
