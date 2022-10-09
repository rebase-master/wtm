<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abhinav
 * Date: 24/01/11
 * Time: 8:20 PM
 * Publishes the album to multiple sources
 */

class Phototour_Publisher_Album
{
    function publishFacebook($params)
    {
        $access_token = $params['access_token'];
        $fb_uid = $params['fb_uid'];
        $album_name = $params['album_name'];
        $album_url = $params['album_url'];
        $album_picture = $params['album_picture'];

        $apiUrl = 'https://graph.facebook.com/' . $fb_uid . "/links";
        $client = new Zend_Http_Client($apiUrl);
        $client->setMethod(Zend_Http_Client::POST);

        $client->setParameterPost('link', $album_url);
        $client->setParameterPost('name', $album_name);
        $client->setParameterPost('caption', 'Uploaded on phototour.in');
        $client->setParameterPost('picture', $album_picture);

        try {
            $response = $client->request();
            Phototour_Logger::log($response->getBody());
        }
        catch(Exception $e) {
            return false;
        }                
    }

    function publishTwitter($params) {
        $token = unserialize($params['access_token']);

        $album_name = $params['album_name'];
        $album_url = $params['album_url'];
        $album_picture = $params['album_picture'];
        $status = "Just uploaded an album " . $album_name . " on PhotoTour. " . $album_url;
        $apiUrl = 'http://api.twitter.com/1/statuses/update.json';

        $config = Zend_Registry::get("config");
        $twitterCallbackUrl = SITE_URL . "/profile/connect-twitter-callback";
        $twitterSiteUrl = $config->constants->twitterSiteUrl;
        $twitterConsumerKey = $config->constants->twitterConsumerKey;
        $twitterConsumerSecret = $config->constants->twitterConsumerSecret;

        $oauthConfig = array(
            'callbackUrl' => $twitterCallbackUrl,
            'siteUrl' => $twitterSiteUrl,
            'consumerKey' => $twitterConsumerKey,
            'consumerSecret' => $twitterConsumerSecret
        );

        $status = "Just uploaded an album '" . $album_name . "' on PhotoTour. " . $album_url;

        $client = $token->getHttpClient($oauthConfig);
        $client->setUri($apiUrl);

        $client->setMethod(Zend_Http_Client::POST);

        $client->setParameterPost('status', $status);

        try {
            $response = $client->request();
        }
        catch(Exception $e) {
            return false;
        }
    }

    function publishTumblr($params) {
        $token = unserialize($params['access_token']);

        $album_name = $params['album_name'];
        $album_url = $params['album_url'];
        $album_picture = $params['album_picture'];
        $status = "Just uploaded an album " . $album_name . " on PhotoTour. " . $album_url;
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
        $client->setParameterPost('title', $album_name);
//        $client->setParameterPost('body', $album_url);
//        $client->setParameterPost('description', $status);

        try {
            $response = $client->request();            
        }
        catch(Exception $e) {
            return false;
        }
    }
}