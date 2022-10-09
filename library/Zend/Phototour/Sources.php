<?php
require_once("MultiCurl.class.php");
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

$init = microtime(true);

$allPhotos = array();

function writeStuff($stuff) {
}
//Calculates Harvesine distance between two points
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
	$R = 6371.0072;
	$lat1 = $lat1 * (M_PI / 180);
	$lon1 = $lon1 * (M_PI / 180);
	$lat2 = $lat2 * (M_PI / 180);
	$lon2 = $lon2 * (M_PI / 180);

	$diffLat = $lat2 - $lat1;
	$diffLon = $lon2 - $lon1;
	$a = sin($diffLat/2) * sin($diffLat/2) + cos($lat1) * cos($lat2) * sin($diffLon/2) * sin($diffLon/2);
	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
	$d = $R * $c;
	return $d;
}

class MyMultiCurl extends MultiCurl {
	public $panoramioUrl;
	public $flickrUrl;
	public $geographUrl;
	public $showData;

	protected function onLoad($url, $content, $info) {
		if(strcmp($url, $this->panoramioUrl) == 0) {
			handlePanoramio($content, $this->showData);
		}

		if(strcmp($url, $this->flickrUrl) == 0) {
			handleFlickr($content,$this->showData);
		}

		if(strcmp($url, $this->geographUrl) == 0) {
			handleGeograph($content, $this->showData);
		}
	}
}

function handlePanoramio($content, $showData) {
	global $allPhotos;
	global $dbApi;
	//echo $content;
	$ar = json_decode($content, true);

	$photos = $ar['photos'];
	$sql = "INSERT IGNORE `".$dbApi."`.`mapsource_panoramio` (
				`photo_title` ,
				`photo_id` ,
				`photo_file_url` ,
				`width` ,
				`height` ,
				`latitude` ,
				`longitude` ,
				`upload_date` ,
				`owner_name` ,
				`owner_url` ,
				`owner_id` ,
				`photo_url`,
				`thumbnail_url`
				)
				VALUES ";

	$sqlEnv = "INSERT IGNORE `".$dbApi."`.`environments` (
				`photo_id` ,
				`type` ,
				`unique_id`,
				`lat` ,
				`lng` ,
				`width` ,
				`height` ,
				`image_url` ,
				`name` ,
				`created_at`
				)
				VALUES ";

	$count = 0;
	foreach($photos as $photo) {
		$upload_date = addslashes($photo['upload_date']);
		$owner_name = addslashes($photo['owner_name']);
		$photo_id = $photo['photo_id'];
		$longitude = $photo['longitude'];
		$height = $photo['height'];
		$width = $photo['width'];
		$photo_title = addslashes($photo['photo_title']);
		$latitude = $photo['latitude'];
		$owner_url = addslashes($photo['owner_url']);
		$owner_id = $photo['owner_id'];
		$photo_url = addslashes($photo['photo_url']);
		$photo_file_url = addslashes($photo['photo_file_url']);

		$thumbnail_url = str_replace("medium", "thumbnail", $photo_file_url);

		$unique_id = "panoramio_photo_" . $photo_id;

		$sql .= "(
			'$photo_title',  '$photo_id',  '$photo_file_url',  $width,
			$height,  $latitude,  $longitude,  '$upload_date',
					'$owner_name',  '$owner_url',  '$owner_id',  '$photo_url', '$thumbnail_url'
				),";

		$sqlEnv .= "(
			'$photo_id',  'panoramio_photo',  '$unique_id', '$latitude',  '$longitude',
			'$width', '$height',  '$photo_file_url',
			'$photo_title',  NOW()
				),";

		$el = array();
		$el['type'] = "panoramio_photo";
		$el['id'] = $photo_id . "";
		$el['image_url'] = $photo_file_url;
		$el['lat'] = $latitude;
		$el['lng'] = $longitude;
		$el['width'] = $width;
		$el['height'] = $height;
		$el['label'] = $photo_title;
		$el['thumbnail_url'] = $thumbnail_url;
		$el['data'] = array();

		$allPhotos[] = $el;

		$count++;
	}

	if($count > 0) {
		$sql = substr($sql, 0, strlen($sql)-1);
		$sql .= ";";
		$result = mysql_query($sql) or die("Could not execute query" . mysql_error());

		$sqlEnv = substr($sqlEnv, 0, strlen($sqlEnv)-1);
		$sqlEnv .= ";";
		$resultEnv = mysql_query($sqlEnv) or die("Could not execute query" . mysql_error());
	}
}

function handleFlickr($content, $showData) {
	global $allPhotos, $dbApi;

	if($content == NULL) {
		return false;
	}

	$sx = simplexml_load_string($content);

	$sql = "INSERT IGNORE `".$dbApi."`.`mapsource_flickr` (
				`photo_id`,
				`owner`,
				`secret`,
				`server`,
				`farm`,
				`title`,
				`ispublic`,
				`isfriend`,
				`isfamily`,
				`image_url`,
				`width`,
				`height`,
				`thumbnail_url`,
				`thumbnail_width`,
				`thumbnail_height`,
				`latitude`,
				`longitude`,
				`place_id`
				)
				VALUES ";

	$sqlEnv = "INSERT IGNORE `".$dbApi."`.`environments` (
				`photo_id` ,
				`type` ,
				`unique_id`,
				`lat` ,
				`lng` ,
				`width` ,
				`height` ,
				`image_url` ,
				`name` ,
				`created_at`
				)
				VALUES ";

	$count = 0;
	foreach($sx->photos->photo as $photo) {
		$a = $photo->attributes();

		$id = addslashes((String)$a->id);
		$owner = addslashes((String)$a->owner);
		$secret = addslashes((String)$a->secret);
		$server = (String)$a->server;
		$farm = addslashes((String)$a->farm);
		$title = addslashes((String)$a->title);
		$ispublic = (int)$a->ispublic;
		$isfriend = (int)$a->isfriend;
		$isfamily = (int)$a->isfamily;

		$image_url = addslashes((String)$a->url_m);
		$width = (int)$a->width_m;
		$height = (int)$a->height_m;

		$thumbnail_url = NULL;
		$thumbnail_width = NULL;
		$thumbnail_height = NULL;

		$latitude = (float)$a->latitude;
		$longitude = (float)$a->longitude;

		$place_id = addslashes((String)$a->place_id);

		$sql .= "(
			'$id', '$owner',  '$secret',  '$server',  '$farm',
			'$title',  $ispublic,  $isfriend, $isfamily,
			'$image_url',  $width,  $height,
			'$thumbnail_url', '$thumbnail_width', '$thumbnail_height',
			$latitude, $longitude, '$place_id'
				),";


		$unique_id = "flickr_photo_" . $id;

		$sqlEnv .= "(
			'$id',  'flickr_photo',  '$unique_id', '$latitude',  '$longitude',
			'$width', '$height',  '$image_url',
			'$title',  NOW()
				),";

		$el = array();
		$el['type'] = "flickr_photo";
		$el['id'] = $id;
		$el['image_url'] = $image_url;
		$el['lat'] = $latitude;
		$el['lng'] = $longitude;
		$el['width'] = $width;
		$el['height'] = $height;
		$el['label'] = $title;
		$el['data'] = array();

		$count++;
		$el['thumbnail'] = array();
		$allPhotos[] = $el;
	}

	if($count > 0) {
		$sql = substr($sql, 0, strlen($sql)-1);
		$sql .= ";";
		$result = mysql_query($sql) or die("Could not execute query." . mysql_error());

		$sqlEnv = substr($sqlEnv, 0, strlen($sqlEnv)-1);
		$sqlEnv .= ";";

		$resultEnv = mysql_query($sqlEnv) or die("Could not execute query");
	}

}

function handleGeograph($content, $showData) {
	global $allPhotos, $dbApi;

	if($content == NULL) {
		return false;
	}

	$count = 0;

	$sx = simplexml_load_string($content);
	$sql = "INSERT IGNORE `".$dbApi."`.`mapsource_geograph` (
				`photo_id` ,
				`geograph_url` ,
				`title` ,
				`owner_name` ,
				`owner_url` ,
				`thumbnail_url` ,
				`thumbnail_width` ,
				`thumbnail_height` ,
				`image_url` ,
				`image_width` ,
				`image_height` ,
				`location_grid` ,
				`latitude`,
				`longitude`
				)
				VALUES ";

	$sqlEnv = "INSERT IGNORE `".$dbApi."`.`environments` (
				`photo_id` ,
				`type` ,
				`unique_id`,
				`lat` ,
				`lng` ,
				`width` ,
				`height` ,
				`image_url` ,
				`name` ,
				`created_at`
				)
				VALUES ";
				
	$count = 0;
	foreach($sx->image as $image) {
		/*
		$geograph_url = addslashes((String)$image->attributes()->url);

		$a = explode("/", $geograph_url);
		$photo_id = $a[count($a)-1];
		$title = addslashes((String)$image->title);
		$owner_name = addslashes((String)$image->user);
		$owner_url = addslashes((String)$image->user->attributes()->profile);
		$thumbnail_url = addslashes((String)$image->img->attributes()->src);
		$thumbnail_height = (int)$image->img->attributes()->height;
		$thumbnail_width = (int)$image->img->attributes()->width;
		$location_grid = (String)$image->location->attributes()->grid;
		$lat = (float)$image->location->attributes()->lat;
		$lng = (float)$image->location->attributes()->long;
		
		
		
		$sql .= "(
			'$photo_id', '$geograph_url',  '$title',  '$owner_name',  '$owner_url',
			'$thumbnail_url',  $thumbnail_width,  $thumbnail_height,
			NULL,  NULL,  NULL,  '$location_grid', $lat,  $lng
				),";
		*/
		
		$photo_id = addslashes((String)$image->photo_id);
		$title = addslashes((String)$image->title);
		$owner_name = addslashes((String)$image->realname);
		$lat = addslashes((String)$image->latitude);
		$lng = addslashes((String)$image->longitude);
		$location_grid = addslashes((String)$image->grid_reference);
		$thumbnail_url = NULL;
		$thumbnail_width = NULL;
		$thumbnail_height = NULL;
		
		$sql .= "(
			'$photo_id', '',  '$title',  '$owner_name',  '',
			NULL, NULL, NULL,
			NULL,  NULL,  NULL,  '$location_grid', $lat,  $lng
				),";
				
		$unique_id = "geograph_photo_" . $photo_id;

		$sqlEnv .= "(
			'$photo_id',  'geograph_photo',  '$unique_id', '$lat',  '$lng',
			NULL, NULL,  NULL,
			'$title',  NOW()
			),";

		$el = array();
		$el['type'] = "geograph_photo";
		$el['id'] = $photo_id . "";
		$el['image_url'] = '';
		$el['lat'] = $lat;
		$el['lng'] = $lng;
		$el['width'] = '';
		$el['height'] = '';
		$el['label'] = $title;
		$el['data'] = array();

		$count++;
		$el['thumbnail'] = array();
		$allPhotos[] = $el;
	}

	if($count > 0) {
		$sql = substr($sql, 0, strlen($sql)-1);
		$sql .= ";";

		$result = mysql_query($sql) or die("Could not execute query." . mysql_error());
		
		$sqlEnv = substr($sqlEnv, 0, strlen($sqlEnv)-1);
		$sqlEnv .= ";";

		$resultEnv = mysql_query($sqlEnv) or die("Could not execute query.<br/> $sqlEnv" . mysql_error());
	}
}

class Phototour_Sources {
	//Lat/Lng calculations available on http://www.movable-type.co.uk/scripts/latlong.html

	public function __construct() {

	}

	//Calculates Harvesine distance between two points
	public function calculateDistance($lat1, $lon1, $lat2, $lon2) {
		$R = 6371.0072;
		$lat1 = $lat1 * (M_PI / 180);
		$lon1 = $lon1 * (M_PI / 180);
		$lat2 = $lat2 * (M_PI / 180);
		$lon2 = $lon2 * (M_PI / 180);

		$diffLat = $lat2 - $lat1;
		$diffLon = $lon2 - $lon1;
		$a = sin($diffLat/2) * sin($diffLat/2) + cos($lat1) * cos($lat2) * sin($diffLon/2) * sin($diffLon/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $R * $c;
		return $d;
	}

	public function calculateDestination($centerLat, $centerLng, $bearing, $distance) {
		$R = 6371.0072;
		$d = $distance;
		$lat1 = $centerLat;
		$lon1 = $centerLng;

		$d = $distance;

		$lat1 = $lat1 * (M_PI / 180);
		$lon1 = $lon1 * (M_PI / 180);
		$theta = $bearing * (M_PI / 180);

		$lat2 = asin(sin($lat1) * cos($d/$R) + cos($lat1) * sin($d/$R) * cos($theta));
		$lon2 = $lon1 + atan2(sin($theta) * sin($d/$R) * cos($lat1), cos($d/$R) - sin($lat1) * sin($lat2));

		$c = array();
		$c['lat'] = $lat2 * (180 / M_PI);
		$c['lng'] = $lon2 * (180 / M_PI);

		return $c;
	}

	//Calculates the bounds given a radius in Kilometres
	public function calculateBounds($centerLat, $centerLng, $radius) {
		$ne = $this->calculateDestination($centerLat, $centerLng, 45, $radius);
		$sw = $this->calculateDestination($centerLat, $centerLng, 225, $radius);

		//minx = sw lng - From Phototour flash code
		//miny = sw lat
		//maxx = ne lng
		//maxy = ne lat
		$bounds = array();
		$bounds['minx'] = round($sw['lng'], 5);
		$bounds['miny'] = round($sw['lat'], 5);
		$bounds['maxx'] = round($ne['lng'], 5);
		$bounds['maxy'] = round($ne['lat'], 5);

		return $bounds;
	}

	//Gets photos from all the sources and returns the data as an array
	public function get($centerLat, $centerLng, $radius) {
		global $allPhotos;
		if(!$radius) {
			$radius = 0.15;
		}
		$bounds = array();
		$bounds = $this->calculateBounds($centerLat, $centerLng, $radius);

		$to = 25;
		$from = 0;

		$params = array();
		$params['minx'] = $bounds['minx'];
		$params['miny'] = $bounds['miny'];
		$params['maxx'] = $bounds['maxx'];
		$params['maxy'] = $bounds['maxy'];
		$params['center_lat'] = $centerLat;
		$params['center_lng'] = $centerLng;
		$params['to'] = 25;
		$params['from'] = 0;
		$params['show_data'] = false;
		$params['size'] = "medium";

		$data = $this->getWithinBounds($params);

		$count = 0;
		$dPhotos = $data['photos'];

		$photos = array();


		foreach($dPhotos as $item) {
			$photoItem = $item;
			$photoItem['distance'] = $this->calculateDistance($item['lat'],
																$item['lng'],
																$centerLat,
																$centerLng);
			$photos[] = $photoItem;
		}

		function cmp($a, $b) {
			if($a['distance'] < $b['distance'])
				return -1;
			else if($a['distance'] == $b['distance'])
				return 0;
			else
				return 1;
		}

		usort($photos, "cmp");
		return $photos;
	}

	private function getMapsourcePhototour($minx, $miny, $maxx, $maxy, $limit) {
		global $allPhotos;

		$logger = Zend_Registry::get("logger");

		$sqlEnv = "SELECT photo_id,width,height,longitude,latitude,photo_file_url,photo_title,AsText(location) FROM mapsource_phototour
					 WHERE MBRContains(GeomFromText('LineString($miny $minx, $maxy $maxx)'),location)
					 		AND environment_exists = 1 AND is_public = 1 AND latitude <> 0 AND longitude <> 0 AND latitude IS NOT NULL
					 		AND longitude IS NOT NULL
					ORDER BY upload_date DESC LIMIT 0, $limit";

		$resultEnv = mysql_query($sqlEnv) or die("Could not execute query $sqlEnv");
		while($photo = mysql_fetch_array($resultEnv)) {
			$el['type'] = "phototour_photo";
			$el['id'] = $photo['photo_id'] . "";
			$el['image_url'] = $photo['photo_file_url'];
			$el['lat'] = $photo['latitude'];
			$el['lng'] = $photo['longitude'];
			$el['width'] = $photo['width'];
			$el['height'] = $photo['height'];
			$el['label'] = stripslashes($photo['photo_title']);

			$allPhotos[] = $el;
		}
	}

	public function getWithinBounds($params) {
		global $file;
		global $allPhotos;
		$minx = $params['minx'];
		$miny = $params['miny'];
		$maxx = $params['maxx'];
		$maxy = $params['maxy'];
		$center_lat = $params['center_lat'];
		$center_lng = $params['center_lng'];
		if(!isset($params['radius'])) {
			$radius = NULL;
		}
		else {
			$radius = $params['radius'];
		}
		$to = $params['to'];
		$from = $params['from'];
		$panoramio_size = $params['size'];
		$showData = $params['show_data'];

		if($center_lat && $center_lng && $radius==null) {
			$radius = round(calculateDistance($miny, $minx, $center_lat, $center_lng), 2)/1.2;
			$center_lat = round($center_lat, 6);
			$center_lng = round($center_lng, 6);
		}

		if($center_lat != null && $center_lng != null && $radius != null && $minx == null) {
			$bounds = $this->calculateBounds($center_lat, $center_lng, $radius);
			$minx = $bounds['minx'];
			$miny = $bounds['miny'];
			$maxx = $bounds['maxx'];
			$maxy = $bounds['maxy'];
		}

		//echo $geographUrl;
		$allPhotos = array();

		$this->getMapsourcePhototour($minx, $miny, $maxx, $maxy, $to);

		$panoramioUrl = 'http://www.panoramio.com/map/get_panoramas.php?order=popularity&set=full&from=' .
				$from. '&to=' . $to .'&minx=' . $minx . '&miny=' . $miny .
				'&maxx=' . $maxx . '&maxy=' . $maxy . '&size=' . $panoramio_size;

		$flickrUrl = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=4069fc054decea8498c0b09adbd1e9f1&extras=geo,url_m';
		$flickrUrl .= '&sort=relevance&per_page=' . 25;

		if($radius >= 32) {
			$flickrUrl .= "&bbox=$minx,$miny,$maxx,$maxy";
		}
		else {
			$flickrUrl .= "&radius=$radius&lat=$center_lat&lon=$center_lng";
		}

		$flickrUrl .= "&bbox=$minx,$miny,$maxx,$maxy";
		$flickrUrl .= '&content_type=photos';
		$flickrUrl .= '&has_geo=1&per_page=150&min_upload_date=' . 0;

		//$geographUrl = 'http://api.geograph.org.uk/api/latlong/'. $radius .'km/'. $center_lat . ',' . $center_lng . '/[apiKey]';
		//$geographUrl = "http://localhost:8888/bits360_v2/trunk/phototour/production/sources/geograph.php";
		//$geographUrl .= "?from=$from" . '&to=' . $to .'&minx=' . $minx . '&miny=' . $miny .
		//				'&maxx=' . $maxx . '&maxy=' . $maxy;
		
		$geographUrl = "http://www.vindev.net/phototourv2/public/geograph.php?limit=$to&minx=$minx&miny=$miny&maxx=$maxx&maxy=$maxy";

		$curlOptions = array();
		$curlOptions[CURLOPT_TIMEOUT] = 30;
		$mc = new MyMultiCurl();
		$mc->flickrUrl = $flickrUrl;
		$mc->panoramioUrl = $panoramioUrl;

		$mc->geographUrl = $geographUrl;
		$mc->showData = $showData;

		$mc->setMaxSessions(3); // limit 2 parallel sessions (by default 10)
//		$mc->addUrl($panoramioUrl);
//		$mc->addUrl($flickrUrl);
//		$mc->addUrl($geographUrl);

		//Geograph only works for these values
		if($radius < 40) {
			//Disable geograph until we host the database ourselves
			//$mc->addUrl($geographUrl);
		}
		$mc->wait();
		//Output all values as JSON
		$outAr = array();
		$outAr['photos'] = $allPhotos;
		return $outAr;
	}
}