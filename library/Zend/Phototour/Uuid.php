<?php
//Thumbnail creation script for use with phototour
class Phototour_Uuid
{

    public function __construct()
    {
        if (Zend_Registry::isRegistered('logger')) {
            $logger = Zend_Registry::get('logger');
            $this->logger = $logger;
        }
    }

    //From http://php.net/manual/en/function.uniqid.php
    // http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
    public static function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function gen_bin_uuid()
    {
        $uuid = Phototour_Uuid::gen_uuid();
        Phototour_Logger::log("UUID: " .$uuid);
        $bin = pack("H*", str_replace('-', '', $uuid));
        return $bin;
    }

    public static function get_unpacked_uuid($uuid)
    {
        $unpackedAr = unpack('H*', $uuid);
        return $unpackedAr[1];
    }

    public static function get_packed_uuid($uuid_unpacked)
    {
        return pack("H*", $uuid_unpacked);
    }

}

?>