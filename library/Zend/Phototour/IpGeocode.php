<?php
require_once("Zend/Registry.php");
$config = Zend_Registry::get("config");

global $dbApi;
global $dbIpGeocode;

$dbHost = $config->resources->db->params->host;
$dbUsername = $config->resources->db->params->username;
$dbPassword = $config->resources->db->params->password;
$dbName = $config->resources->db->params->dbname;
$dbApi = $config->resources->db->params->dbname;
$dbIpGeocode = $config->resources->db->params->dbname;

mysql_connect($dbHost, $dbUsername, $dbPassword);
mysql_select_db($dbApi);

class Phototour_IpGeocode
{
	public function __construct() {

	}

	public function getLocation($ipAddress) {
		global $dbIpGeocode;
		$ipArray = explode('.',$ipAddress);
		if(count($ipArray) != 4) {
			return NULL;
		}

		$ipNumber = 16777216*$ipArray[0] + 65536*$ipArray[1] + 256*$ipArray[2]+ $ipArray[3];

		$dbIp = mysql_select_db($dbIpGeocode);

		$ipQuery = "Select * from ipgeocode where startIpNum < ".$ipNumber. " and endIpNum > ".$ipNumber;
		$ipResult = mysql_query($ipQuery);

		if (mysql_num_rows($ipResult) > 0) {
			$ipResultRow = mysql_fetch_row($ipResult);
			$locationId = $ipResultRow[3];

			$locationQuery = "Select * from locationinfo where locId = ".$locationId;
			$locationResult = mysql_query($locationQuery);

			if (mysql_num_rows($locationResult) > 0){
				$locationResultRow = mysql_fetch_row($locationResult);
				$locCountry = $locationResultRow[1];
				$locRegion = $locationResultRow[2];
				$locCity = $locationResultRow[3];
				$locPcode = $locationResultRow[4];
				$locLatitude = $locationResultRow[5];
				$locLongitude = $locationResultRow[6];
				$locMetroCode = $locationResultRow[7];
				$locAreaCode = $locationResultRow[8];

				$startLat = $locLatitude;
				$startLng= $locLongitude;
			}
		}

		if(!isset($startLat)) {
			$startLat = -1;
			$startLng = -1;
		}

		$ret = array();
		$ret['lat'] = $startLat;
		$ret['lng'] = $startLng;

		return $ret;
	}
}