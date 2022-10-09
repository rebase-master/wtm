<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abhinav
 * Date: 24/01/11
 * Time: 8:37 AM
 * To change this template use File | Settings | File Templates.
 */
 
class Phototour_Notifications_Email {
    function sendSignupNotification($params) {

    }


    function sendUploadNotification($params) {

    }

    function sendCommentNotification($params) {        
        $logger = Zend_Registry::get("logger");

        $username = $params['username'];
        $user_id = $params['user_id'];
        $environment_id = $params['environment_id'];
        $environment_name = $params['environment_name'];
        $environment_user_email = $params['environment_user_email'];
        $environment_user_username = $params['environment_user_username'];
        $profile_url = $params['profile_url'];
        $environment_url = SITE_URL . "/view#!/photo/" . $environment_id;

        $profilePicUrl = API_PHOTOTOUR . "/users/" . $user_id . "/image/64_64";
        $notification_manager_url = SITE_URL . "/profile/edit-notifications";
        $defaultProfileUrl = SITE_URL . "/profile/";
        $html = <<<EMAIL
            <html>
            <head>
            </head>
            <body style="padding: 20px; border: solid thick #ccc; margin: 10px; margin-top: 5px;">
                <h1><img src="http://www.phototour.in/img/design2/logo.png"/></h1>
                <h2>Hi <a href="{$defaultProfileUrl}">{$environment_user_username}</a></h2>
                <div style="height: 100px;">
                    <div style="float: left;"><img src="{$profilePicUrl}"/></div>
                    <div style="float: left; margin-left: 10px;">
                        <a href="{$profile_url}">{$username}</a> just commented on your photo
                        "<a href="{$environment_url}">{$environment_name}</a>".
                        <br/><br/>
                        Comment - What does it mean?<br/>
                        <strong>{$username}</strong> wanted to say something really interesting about the photo.
                    </div>
                </div>
                <div style="font-size: 10px;">
                    The message was sent to {$environment_user_email}.
                    To manage your notifications <a href="{$notification_manager_url}">click here</a>
                </div>
            </body>
            </html>
EMAIL;

        $subject = $username . " just commented on your photo.";
        $mail = new Zend_Mail();
        $mail->setBodyHtml($html);
        $mail->setFrom('notifications@phototour.in', 'PhotoTour');
        $mail->addTo($environment_user_email, $environment_user_username);
        $mail->setSubject($subject);

        $mail->send();
    }

    function sendFollowUpCommentNotification($params) {
        $config = Zend_Registry::get('config');

        if($config->project == 'vtcandroid360') {
            return false;
        }

        $logger = Zend_Registry::get("logger");

        //The guy who wrote this comment
        $username = $params['username'];
        $user_id = $params['user_id'];

        $environment_id = $params['environment_id'];
        $environment_name = $params['environment_name'];

        //The guy who wrote a previous comment
        $previous_user_email = $params['previous_user_email'];
        $previous_user_username = $params['previous_user_username'];

        $profile_url = $params['profile_url'];
        $environment_url = SITE_URL . "/view#!/photo/" . $environment_id;

        $profilePicUrl = API_PHOTOTOUR . "/users/" . $user_id . "/image/64_64";
        $notification_manager_url = SITE_URL . "/profile/edit-notifications";
        $defaultProfileUrl = SITE_URL . "/profile/";
        $html = <<<EMAIL
            <html>
            <head>
            </head>
            <body style="padding: 20px; border: solid thick #ccc; margin: 10px; margin-top: 5px;">
                <h1><img src="http://www.phototour.in/img/design2/logo.png"/></h1>
                <h2>Hi <a href="{$defaultProfileUrl}">{$previous_user_username}</a></h2>
                <div style="height: 100px;">
                    <div style="float: left;"><img src="{$profilePicUrl}"/></div>
                    <div style="float: left; margin-left: 10px;">
                        <a href="{$profile_url}">{$username}</a> just replied to your comment on the photo
                        "<a href="{$environment_url}">{$environment_name}</a>".
                    </div>
                </div>
                <div style="font-size: 10px;">
                    The message was sent to {$previous_user_email}.
                    To manage your notifications <a href="{$notification_manager_url}">click here</a>
                </div>
            </body>
            </html>
EMAIL;

        $subject = $username . " just replied to your comment.";

        $mail = new Zend_Mail();
        $mail->setBodyHtml($html);
        $mail->setFrom('notifications@phototour.in', 'PhotoTour');
        $mail->addTo($previous_user_email, $previous_user_username);
        $mail->setSubject($subject);

        $mail->send();
    }

    function sendLikeNotification($params) {
        $config = Zend_Registry::get('config');

        if($config->project == 'vtcandroid360') {
            return false;
        }

        $logger = Zend_Registry::get("logger");

        $username = $params['username'];
        $user_id = $params['user_id'];
        $environment_id = $params['environment_id'];
        $environment_name = $params['environment_name'];
        $environment_user_email = $params['environment_user_email'];
        $environment_user_username = $params['environment_user_username'];
        $profile_url = $params['profile_url'];
        $environment_url = SITE_URL . "/view#!/photo/" . $environment_id;

        $logger->info($environment_user_email);
        $logger->info($environment_user_username);
        $logger->info($username);

        $profilePicUrl = API_PHOTOTOUR . "/users/" . $user_id . "/image/64_64";
        $notification_manager_url = SITE_URL . "/profile/edit-notifications";
        $defaultProfileUrl = SITE_URL . "/profile/";
        $html = <<<EMAIL
            <html>
            <head>
            </head>
            <body style="padding: 20px; border: solid thick #ccc; margin: 10px; margin-top: 5px;">
                <h1><img src="http://www.phototour.in/img/design2/logo.png"/></h1>
                <h2>Hi <a href="{$defaultProfileUrl}">{$environment_user_username}</a></h2>
                <div style="height: 100px;">
                    <div style="float: left;"><img src="{$profilePicUrl}"/></div>
                    <div style="float: left; margin-left: 10px;">
                        <a href="{$profile_url}">{$username}</a> just faved your photo
                        "<a href="{$environment_url}">{$environment_name}</a>".
                        <br/><br/>
                        Fav - What does it mean?<br/>
                        <strong>{$username}</strong> loves your photo.. you superstar photographer. :)
                    </div>
                </div>
                <div style="font-size: 10px;">
                    The message was sent to {$environment_user_email}.
                    To manage your notifications <a href="{$notification_manager_url}">click here</a>
                </div>
            </body>
            </html>
EMAIL;


        $logger->info($html);
        $subject = $username . " just faved your photo.";
        $mail = new Zend_Mail();
        $mail->setBodyHtml($html);
        $mail->setFrom('notifications@phototour.in', 'PhotoTour');
        $mail->addTo($environment_user_email, $environment_user_username);
        $mail->setSubject($subject);

        $mail->send();
        $logger->info("Mail sent");
    }

    function sendFollowNotification($params) {
        $config = Zend_Registry::get('config');

        if($config->project == 'vtcandroid360') {
            return false;
        }
        
        $logger = Zend_Registry::get("logger");

        $followed_user_id = $params['followed_user_id'];
        $followed_username = $params['followed_username'];
        $followed_email = $params['followed_email'];
        $followed_profile_url = $params['followed_profile_url'];

        $follower_user_id = $params['follower_user_id'];
        $follower_username = $params['follower_username'];
        $follower_profile_url = $params['follower_profile_url'];

        $profilePicUrl = API_PHOTOTOUR . "/users/" . $follower_user_id . "/image/64_64";
        $notification_manager_url = SITE_URL . "/profile/edit-notifications";
        $html = <<<EMAIL
            <html>
            <head>
            </head>
            <body style="padding: 20px; border: solid thick #ccc; margin: 10px; margin-top: 5px;">
                <h1><img src="http://www.phototour.in/img/design2/logo.png"/></h1>
                <div style="height: 100px;">
                    <h2>Hi <a href="{$followed_profile_url}">{$followed_username}</a></h2>
                    <div style="float: left;"><img src="{$profilePicUrl}"/></div>
                    <div style="float: left; margin-left: 10px;">
                        <a href="{$follower_profile_url}">{$follower_username}</a> just started following you on PhotoTour.
                        <br/><br/>
                        Follow - What does it mean?<br/>
                        <strong>{$follower_username}</strong> thinks you are supercool and wants to get updates about you.
                    </div>
                </div>
                <div style="font-size: 10px;">
                    The message was sent to {$followed_email}.
                    To manage your notifications <a href="{$notification_manager_url}">click here</a>
                </div>
            </body>
            </html>
EMAIL;


        $logger->info($html);
        $subject = $follower_username . " is now following you on PhotoTour.";
        $mail = new Zend_Mail();
        $mail->setBodyHtml($html);
        $mail->setFrom('notifications@phototour.in', 'PhotoTour');
        $mail->addTo($followed_email, $followed_username);
        $mail->setSubject($subject);

        $mail->send();
        $logger->info("Mail sent");
    }
}
