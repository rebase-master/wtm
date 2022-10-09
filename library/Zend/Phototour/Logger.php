<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abhinav
 * Date: 26/01/11
 * Time: 11:15 AM
 * To change this template use File | Settings | File Templates.
 */

class Phototour_Logger
{
    static function log($msg)
    {
        $level = 1;
        $trace = debug_backtrace();

        Zend_Registry::get("logger")->info($msg);

        if (is_a($msg, 'Exception')) {
            if ((APPLICATION_ENV == 'staging') || (APPLICATION_ENV == 'production')) {

                $client = Zend_Registry::get("raven_client");
                $client->captureException($msg);
            }
        }
    }

    static function log_dev($msg)
    {
        if (APPLICATION_ENV == 'production') {

//            $settings = Zend_Registry::get('config');
//            $url = $settings->urls->dsnTmapiUrl;
//            $url = "http://e16bd78c1c21496286c369c260248e5f:2e0bf7cd39564ba697ae4a68be123743@192.81.134.108/12";
//            $client = new Raven_Client($url);
//            $client->captureException($msg);
        }

        Zend_Registry::get("logger")->info($msg);

    }

    static function log_error($e)
    {
        if ((APPLICATION_ENV == 'staging') || (APPLICATION_ENV == 'production')) {

            $settings = Zend_Registry::get('config');
            $url = $settings->urls->dsnTmapiUrl;
            $client = new Raven_Client($url);
            $client->captureException($e);
        }

        Zend_Registry::get("logger")->info($e);
    }

    static function log_message($e, $params = array())
    {
        {

            $settings = Zend_Registry::get('config');
            $url = $settings->urls->dsnTmapiUrl;
            $client = new Raven_Client($url);
            $client->captureMessage($e, array("sent" => $params));
        }
        Zend_Registry::get("logger")->info($e);
    }
}
