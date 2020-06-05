<?php

//Set Source Path
$sourcepath = '/var/www/put/your/path/here';

//Regex Express to test leading and trailing spaces
define('PRE', "#^[\n\r|\n\r|\n|\r|\s]+<\?php#");
define('POST', "#\?>[\n\r|\n\r|\n|\r|\s]+$#");

clearstatcache();

$root = ereg_replace('/$', '', ereg_replace('[\\]', '/', $sourcepath));

if (m_walk_dir($root, 'check', true) === false) {
    echo "‘{$root}’ is not a valid directory\n";
}


function m_walk_dir($root, $callback, $recursive = true)
{
    $dh = @opendir($root);
    if ($dh === false) {
        return false;
    }
    $file = true;
    while ($file) {
        $file = readdir($dh);
        if ($file === '.' || $file === '..' || $file === 'framework') {
            continue;
        }
        call_user_func($callback, "{$root}/{$file}");
        if ($recursive !== false && is_dir("{$root}/{$file}")) {
            m_walk_dir("{$root}/{$file}", $callback, $recursive);
        }
    }
    closedir($dh);
    return true;
}


function check($path)
{
    if (! is_dir($path)) {

        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: file_get_contents (case sensitive)
         * NEW: file_get_contents (COMPLEX)
         * EXP: Use new asset abstraction (https://docs.silverstripe.org/en/4/changelogs/4.0.0#asset-storage
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
        $fh = file_get_contents($path);
        if (preg_match(PRE, $fh)) {
            echo $path . " — contains leading spaces \n";
        }
        if (preg_match(POST, $fh)) {
            echo $path . " — contains trailing spaces \n";
        }
    }
}
