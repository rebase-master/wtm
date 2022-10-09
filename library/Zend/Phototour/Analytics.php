<?php
class Phototour_Analytics
{
    public function __construct()
    {
    }

    public static function sendToQueue($key, $params)
    {
        $settings = Zend_Registry::get('config');
        $profiler = $settings->features->externalAnalytics;
        if ($profiler == "disabled") {
            return null;
        }

        try {
            $msg_body = json_encode($params);

            $exchange = 'ex_teliportme';
            $queue = 'analytics';

            $conn = new AMQPConnection();
            $conn->connect();

            $ch = new AMQPChannel($conn);

            // Declare a new exchange
            $ex = new AMQPExchange($ch);
            $ex->setName($exchange);
            $ex->setType(AMQP_EX_TYPE_DIRECT);
            $ex->declare();

            // Create a new queue
            $q = new AMQPQueue($ch);
            $q->setName($queue);
            $q->setFlags(AMQP_DURABLE);
            $q->declare();
            // Bind it on the exchange to routing.key
            $routingKey = $key;

            $q->bind($exchange, $routingKey);

            // Publish a message to the exchange with a routing key
            $ex->publish($msg_body, $routingKey);

            $conn->disconnect();
        } catch (Exception $e) {
            $client = Zend_Registry::get("raven_client");
            $client->captureException($e);
            Phototour_Logger::log("Something went wrong while sending Analytics message " . $key);
        }
    }

    public static function logViewed($environmentId, $userId, $accessType, $userIp, $sessionId = 0)
    {
        $project = "360";
        $params = array(
            "user_id" => $userId,
            "session_id" => $sessionId,
            "project" => $project,
            "content_data" => array("environment_id" => $environmentId, "access_type" => $accessType, "user_ip" => $userIp),
            "action" => "viewed",
            "time" => Boffinsbook_Utility::mysqlDate()
        );

        Phototour_Analytics::sendToQueue("api.analytics.action", $params);
    }

    public static function logCommented($environmentId, $userId, $sessionId = 0)
    {
        $project = "360";
        $params = array(
            "user_id" => $userId,
            "session_id" => $sessionId,
            "project" => $project,
            "content_data" => array("environment_id" => $environmentId),
            "action" => "commented",
            "time" => Boffinsbook_Utility::mysqlDate()
        );

        Phototour_Analytics::sendToQueue("api.analytics.action", $params);
    }

    public static function logUploaded($environmentId, $userId, $sessionId = 0)
    {
        $project = "360";
        $params = array(
            "user_id" => $userId,
            "session_id" => $sessionId,
            "project" => $project,
            "content_data" => array("environment_id" => $environmentId),
            "action" => "uploaded",
            "time" => Boffinsbook_Utility::mysqlDate()
        );

        Phototour_Analytics::sendToQueue("api.analytics.action", $params);
    }

    public static function logFaved($environmentId, $userId, $sessionId = 0)
    {
        $project = "360";
        $params = array(
            "user_id" => $userId,
            "session_id" => $sessionId,
            "project" => $project,
            "content_data" => array("environment_id" => $environmentId),
            "action" => "faved",
            "time" => Boffinsbook_Utility::mysqlDate()
        );

        Phototour_Analytics::sendToQueue("api.analytics.action", $params);
    }

    public static function logCaptured($model, $userId, $fov, $mode, $capture_time, $excess_angle, $frame_count = 0, $sessionId = 0)
    {
        $project = "360";
        $contentData = array(
            "fov" => $fov,
            "model" => $model,
            "mode" => $mode,
            "capture_time" => $capture_time,
            "excess_angle" => $excess_angle,
            "frame_count" => $frame_count
        );

        $params = array(
            "user_id" => $userId,
            "session_id" => $sessionId,
            "project" => $project,
            "content_data" => $contentData,
            "action" => "captured",
            "time" => Boffinsbook_Utility::mysqlDate()
        );

        Phototour_Analytics::sendToQueue("api.analytics.action", $params);
    }

    public static function logNewUser($userId)
    {
        $project = "360";

        $params = array(
            "user_id" => $userId,
            "project" => $project,
            "time" => Boffinsbook_Utility::mysqlDate()
        );

        Phototour_Analytics::sendToQueue("api.analytics.user.new", $params);
    }

    public static function logNewUserParams($userId, $model, $version)
    {
        $project = "360";

        $ip = new Api_Model_Ip();

        $params = array(
            "user_id" => $userId,
            "project" => $project,
            "time" => Boffinsbook_Utility::mysqlDate(),
            "user_ip" => $ip->getIp(),
            "version" => $version,
            "model" => $model
        );

        Phototour_Logger::log("New User " . $userId . " Version " . $version . " Model " . $model);

        Phototour_Analytics::sendToQueue("api.analytics.user.new", $params);
    }

    public static function logUpdateUserFbConnection($userId, $isFb)
    {
        $project = "360";

        $params = array(
            "user_id" => $userId,
            "project" => $project,
            "is_fb" => $isFb
        );

        Phototour_Analytics::sendToQueue("api.analytics.user.update", $params);
    }

    public static function logUpdateUserTwitterConnection($userId, $isTwitter)
    {
        $project = "360";

        $params = array(
            "user_id" => $userId,
            "project" => $project,
            "is_twitter" => $isTwitter
        );

        Phototour_Analytics::sendToQueue("api.analytics.user.update", $params);
    }
}
