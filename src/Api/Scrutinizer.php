<?php

namespace Sunnysideup\ModuleChecks\Api;

use Sunnysideup\ModuleChecks\Tasks\UpdateModules;

class Scrutinizer
{
    public static function send_to_scrutinizer($apiKey, $gitHubUserName, $moduleName)
    {
        if (! trim($apiKey)) {
            GeneralMethods::output_to_screen('<li> not Scrutinizer API key set </li>');
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
            GeneralMethods::output_to_screen("<li> could not add ${repoName} to Scrutinizer ... </li>");
            //UpdateModules::$unsolvedItems[$repoName] = "Could not add $reopName to Scrutiniser (curl failure)";

            UpdateModules::addUnsolvedProblem($repoName, "Could not add ${repoName} to Scrutiniser (curl failure)");

            return false;
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode === 201) {
            GeneralMethods::output_to_screen("<li> Added ${repoName} to Scrutinizer ... </li>");
        } else {
            GeneralMethods::output_to_screen("<li> could not add ${repoName} to Scrutinizer ... </li>");
            //UpdateModules::$unsolvedItems[$repoName] = "Could not add $reopName to Scrutiniser (HttpCode $httpcode)";
            UpdateModules::addUnsolvedProblem($repoName, "Could not add ${repoName} to Scrutiniser (HttpCode ${httpcode})");
        }
    }
}
