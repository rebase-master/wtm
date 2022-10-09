<?php
class Phototour_Auth
{
	public function __construct() {

	}

	public function getAuthParams($user_id, $username) {
		$access_token = time() . $username . "blahblah";
		$access_token = md5($access_token);

		$refresh_token = rand(0,100) . $username . "refresh_token";
		$refresh_token = md5($refresh_token);

		$expires_in = 24 * 3600;

		$ret = array();
		$ret['access_token'] = $access_token;
		$ret['refresh_token'] = $refresh_token;
		$ret['expires_in'] = $expires_in;
		return $ret;
	}

    public function getCookie($username) {
        $cookie = $username . "_" . Phototour_Uuid::gen_uuid();
        return $cookie;
    }
}