<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abhinav
 * Date: 24/01/11
 * Time: 8:20 PM
 * Publishes the album to multiple sources
 */

class Phototour_Publisher_Photo
{
    /* @todo [Vikram 1.0]
     * Use standard Fb library
     */

    function publishFacebook($params)
    {
        $access_token = $params['fb_access_token'];
        $fb_uid = $params['fb_uid'];
        $photo_name = $params['photo_name'];
        $photo_url = $params['photo_url'];
        $photo_thumb = $params['photo_thumb'];
        $photo_address = $params['photo_address'];
        $photo_where = $params['photo_where'];

        if (empty($photo_where)) {
            $finalAddress = $photo_address;
        } else {
            if (empty($photo_address)) {
                $finalAddress = $photo_where;
            } else {
                $finalAddress = $photo_where . ", " . $photo_address;
            }
        }

        $status = $photo_name . "@" . $finalAddress . " " . $photo_url;

        $apiUrl = 'https://graph.facebook.com/' . $fb_uid . "/links?access_token=" . urlencode($access_token);


        try {
            $client = new Zend_Http_Client($apiUrl);
            $client->setMethod(Zend_Http_Client::POST);

            $client->setParameterPost('link', $photo_url);
            $client->setParameterPost('name', $status);
            $client->setParameterPost('caption', $status);
            $client->setParameterPost('description', $status);
            $client->setParameterPost('message', $status);
            $client->setParameterPost('picture', $photo_thumb);
            $client->setParameterPost('access_token', $access_token);

            $response = $client->request();
            if ($response != null) {
                if ($response->getStatus() != 200) {
                    Phototour_Logger::log("Something went wrong");
                } else {
                }
            } else {
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /* @todo [Vikram 1.0]
     * use a native library , add thumbnail to tweet
     */

    function publishTwitter($params)
    {
        $token = unserialize($params['access_token']);

        $photo_name = $params['photo_name'];
        $photo_url = $params['photo_url'];
        $photo_thumb = $params['photo_thumb'];
        $photo_address = $params['photo_address'];

        $photo_where = $params['photo_where'];

        if (empty($photo_where)) {
            $finalAddress = $photo_address;
        } else {
            if (empty($photo_address)) {
                $finalAddress = $photo_where;
            } else {
                $finalAddress = ($photo_address == $photo_where) ? $photo_address : $photo_where . ", " . $photo_address;
            }

        }

        $apiUrl = 'http://api.twitter.com/1/statuses/update.json';

        $config = Zend_Registry::get("config");
        $twitterCallbackUrl = SITE_URL . "/connections/connect-twitter-callback";
        $twitterSiteUrl = $config->constants->twitterSiteUrl;
        $twitterConsumerKey = $config->constants->twitterConsumerKey;
        $twitterConsumerSecret = $config->constants->twitterConsumerSecret;

        $oauthConfig = array(
            'callbackUrl' => $twitterCallbackUrl,
            'siteUrl' => $twitterSiteUrl,
            'consumerKey' => $twitterConsumerKey,
            'consumerSecret' => $twitterConsumerSecret
        );

        $photo_name = empty($photo_name) ? ". " : $photo_name;
        $status = $photo_name . "@" . $finalAddress . " " . $photo_url;

        if (strlen($status) < 130) {
            $status .= " #panorama";
        }

        $client = $token->getHttpClient($oauthConfig);
        $client->setUri($apiUrl);

        $client->setMethod(Zend_Http_Client::POST);

        $client->setParameterPost('status', $status);

        try {
            $response = $client->request();
        } catch (Exception $e) {
            Phototour_Logger::log($e->getMessage());
            return false;
        }
    }

    /* @todo [Vikram 1.0]
     * Unused Delete
     */

    function publishTumblr($params)
    {
        $token = unserialize($params['access_token']);

        $photo_name = $params['photo_name'];
        $photo_url = $params['photo_url'];
        $photo_thumb = $params['photo_thumb'];

        $status = "Just uploaded a photo " . $photo_name . " on PhotoTour. " . $photo_url;
        $apiUrl = 'http://www.tumblr.com/api/write';

        $config = Zend_Registry::get("config");
        $tumblrCallbackUrl = SITE_URL . "/profile/connect-tumblr-callback";
        $tumblrSiteUrl = $config->constants->tumblrSiteUrl;
        $tumblrConsumerKey = $config->constants->tumblrConsumerKey;
        $tumblrConsumerSecret = $config->constants->tumblrConsumerSecret;

        $oauthConfig = array(
            'callbackUrl' => $tumblrCallbackUrl,
            'siteUrl' => $tumblrSiteUrl,
            'consumerKey' => $tumblrConsumerKey,
            'consumerSecret' => $tumblrConsumerSecret
        );

        $client = $token->getHttpClient($oauthConfig);
        $client->setUri($apiUrl);

        $client->setMethod(Zend_Http_Client::POST);
        $client->setParameterPost('type', 'regular');
        $client->setParameterPost('title', $photo_name);
        //        $client->setParameterPost('body', $album_url);
        //        $client->setParameterPost('description', $status);

        try {
            $response = $client->request();
        } catch (Exception $e) {
            return false;
        }
    }
}