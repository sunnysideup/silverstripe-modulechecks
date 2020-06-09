<?php

namespace Sunnysideup\ModuleChecks\Api;

use Sunnysideup\ModuleChecks\Tasks\UpdateModules;

class Scrutinizer extends BaseObject
{
    public static function send_to_scrutinizer($apiKey, $gitHubUserName, $moduleName)
    {
        if (! trim($apiKey)) {
            FlushNow::flushNow('No Scrutinizer API key set');
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
            FlushNow::flushNow('Could not add '.$repoName.' to Scrutinizer ... ');
            //UpdateModules::$unsolvedItems[$repoName] = "Could not add $reopName to Scrutiniser (curl failure)";

            UpdateModules::addUnsolvedProblem($repoName, "Could not add ${repoName} to Scrutiniser (curl failure)");

            return false;
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode === 201) {
            FlushNow::flushNow('Added '.$repoName.' to Scrutinizer ... ');
        } else {
            FlushNow::flushNow('Could not add '.$repoName.' to Scrutinizer ... ');
            //UpdateModules::$unsolvedItems[$repoName] = "Could not add $reopName to Scrutiniser (HttpCode $httpcode)";
            UpdateModules::addUnsolvedProblem($repoName, "Could not add ${repoName} to Scrutiniser (HttpCode ${httpcode})");
        }
    }
}
