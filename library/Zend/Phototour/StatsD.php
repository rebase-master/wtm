<?php

/**
 * Sends statistics to the stats daemon over UDP
 *
 **/

class Phototour_StatsD
{

    /**
     * Sets one or more timing values
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $time The elapsed time (ms) to log
     **/
    public static function timing($stats, $time, $key = "tmapi.")
    {
        Phototour_StatsD::updateStats($key . $stats, $time, 1, 'ms');
    }

    /**
     * Sets one or more gauges to a value
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     **/
    public static function gauge($stats, $value, $key = "tmapi.")
    {
        Phototour_StatsD::updateStats($key . $stats, $value, 1, 'g');
    }

    /**
     * A "Set" is a count of unique events.
     * This data type acts like a counter, but supports counting
     * of unique occurences of values between flushes. The backend
     * receives the number of unique events that happened since
     * the last flush.
     *
     * The reference use case involved tracking the number of active
     * and logged in users by sending the current userId of a user
     * with each request with a key of "uniques" (or similar).
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     **/
    public static function set($stats, $value)
    {
        Phototour_StatsD::updateStats($stats, $value, 1, 's');
    }

    /**
     * Increments one or more stats counters
     *
     * @param string|array $stats The metric(s) to increment.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return boolean
     **/
    public static function increment($stats, $sampleRate = 1)
    {
        Phototour_StatsD::updateStats($stats, 1, $sampleRate, 'c');
    }

    /**
     * Decrements one or more stats counters.
     *
     * @param string|array $stats The metric(s) to decrement.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return boolean
     **/
    public static function decrement($stats, $sampleRate = 1)
    {
        Phototour_StatsD::updateStats($stats, -1, $sampleRate, 'c');
    }

    /**
     * Updates one or more stats.
     *
     * @param string|array $stats The metric(s) to update. Should be either a string or array of metrics.
     * @param int|1 $delta The amount to increment/decrement each metric by.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @param string|c $metric The metric type ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
     * @return boolean
     **/
    public static function updateStats($stats, $delta = 1, $sampleRate = 1, $metric = 'c')
    {
        if (!is_array($stats)) {
            $stats = array($stats);
        }
        $data = array();
        foreach ($stats as $stat) {
            $data[$stat] = "$delta|$metric";
        }

        Phototour_StatsD::send($data, $sampleRate);
    }


    public static function timingData($stats, $time, $key = "tmapi.")
    {
        return Phototour_StatsD::updateStatsStore($key . $stats, $time, 1, 'ms');
    }

    public static function gaugeData($stats, $value, $key = "tmapi.")
    {
        return Phototour_StatsD::updateStatsStore($key . $stats, $value, 1, 'g');
    }

    public static function incrementData($stats, $sampleRate = 1, $key = "tmapi.")
    {
        return Phototour_StatsD::updateStatsStore($key . $stats, 1, $sampleRate, 'c');
    }

    public static function updateStatsStore($stats, $delta = 1, $sampleRate = 1, $metric = 'c')
    {
        if (!is_array($stats)) {
            $stats = array($stats);
        }
        $data = array();
        foreach ($stats as $stat) {
            $data[$stat] = "$delta|$metric";
        }

        return $data;
    }

    /*
     * Squirt the metrics over UDP
     **/
    public static function send($data, $sampleRate = 1)
    {

        $settings = Zend_Registry::get('config');
        $profiler = $settings->features->statsd;
        if ($profiler == "disabled") {
            return;
        } else {
            $host = $settings->statsd->host;
            $port = $settings->statsd->port;
            $stats_key = $settings->statsd->key;
        }

        // sampling
        $sampledData = array();

        if ($sampleRate < 1) {
            foreach ($data as $stat => $value) {
                if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
                    $sampledData[$stat] = "$value|@$sampleRate";
                }
            }
        } else {
            $sampledData = $data;
        }

        if (empty($sampledData)) {
            return;
        }

        // Wrap this in a try/catch - failures in any of this should be silently ignored
        try {
            $fp = fsockopen("udp://$host", $port, $errno, $errstr);
            if (!$fp) {
                return;
            }
            foreach ($sampledData as $stat => $value) {
                fwrite($fp, "$stat:$value");
            }
            fclose($fp);
        } catch (Exception $e) {
            Phototour_Logger::log($e);
        }
    }
}


