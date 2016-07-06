<?php

/**
 * main class running all the updates
 *
 *
 */
class UpdateModules extends BuildTask
{

    private static $github_account_base_url = '';

    private static $github_account_base_ssh = 'git@github.com:sunnysideup/';

    /**
     * [code] => [name]
     * e.g.
     *    silverstripe-my-module => 'My Module'
     *
     * @var array
     */
    private static $modules_to_update = array();

    /**
     * e.g.
     * [relative file location] => [srouce location]
     *
     *
     * @var array
     */
    private static $files_to_update = array(

    );

}
