<?php

/**
 * Created by JetBrains PhpStorm.
 * User: vikram
 * Date: 9/20/12
 * Time: 8:41 PM
 * To change this template use File | Settings | File Templates.
 */
class Phototour_Profiler
{

    static function process()
    {

        $process_data = new STDClass();
        $process_data->time = microtime(true);
        $process_data->created_at = time();
        $id = getmypid();
        $process_data->id = $id;
        $process_data->cpu = 0;
        $process_data->ram = memory_get_usage();
        return $process_data;
    }


    static function stopData($db)
    {
        $id = getmypid();
        $data = new STDClass();
        $data->id = $id;
        $data->cpu = 0;
        $data->ram = memory_get_usage();
        $profiler = $db->getProfiler();
        $data->mysql_query = $profiler->getTotalNumQueries();
        $data->mysql_seconds = $profiler->getTotalElapsedSecs();
        $profiler->clear();
        $data->time = microtime(true);
        return $data;
    }

    static function  sendMessage($params, $key = "tmapi.request.log", $queue = 'tmapi_logs', $exchange = 'ex_teliportme')
    {

        if (strpos(APPLICATION_ENV, "test") == true) {
            //Phototour_Logger::log("No message for testing $key");
            return 0;
        }

        try {

            $msg_body = json_encode($params);
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
            return 1;
        } catch (Exception $e) {
            Phototour_Logger::log("Error in Log message $key");
            return 0;
        }

    }

    static function  sendQueueMessage($params, $key = "tmapi.request.log", $queue = 'tmapi_queue', $exchange = 'ex_teliportme')
    {

        if (strpos(APPLICATION_ENV, "test") == true) {
            //Phototour_Logger::log("No message for testing $key");
            return 0;
        }


        try {

            $msg_body = json_encode($params);
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
            return 1;
        } catch (Exception $e) {
//             Phototour_Logger::log("Error in Log message" . print_r($params, true));
            return 0;
        }

    }

}

?>
