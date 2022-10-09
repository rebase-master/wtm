<?php

/**
 * Utility functions
 *
 */
class Phototour_Util
{
    /**
     * Converts an input string with "true"/"false" or any other value to a boolean value
     * 
     * @param $string
     * @return bool
     */
    public static function convertStringToBinary($string) {
        if(strcmp($string, "true") == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function isHttps() {
        if(!empty($_SERVER['SERVER_PORT'])) {
            if($_SERVER['SERVER_PORT'] == "443") {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

    }
}

?>