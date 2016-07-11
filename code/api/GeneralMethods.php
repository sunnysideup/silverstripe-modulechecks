<?php


class GeneralMethods extends Object
{


    /**
     * opens a location with curl to see if it exists.
     *
     * @param string $url
     *
     * @return boolean
     */
    protected function check_location($url) {
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, TRUE);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $outcome = $httpCode == 200;
        curl_close($handle);
        return $outcome;
    }



    /**
     *
     * @use
     * ```
     *    GeneralMethods::output_to_screen('asdf', 'created')
     * ```
     * @see DB::alteration_message for types...
     *
     * @param  string $message
     * @param  string $type
     */
    public static function output_to_screen($message, $type = "")
    {
        echo " ";
        flush(); ob_end_flush(); DB::alteration_message($message, $type); ob_start();
    }


}
