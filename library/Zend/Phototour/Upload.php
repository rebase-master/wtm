<?php
//Thumbnail creation script for use with phototour
class Phototour_Upload
{

	public function __construct() {
	}

	public function uploadPhoto($photoBasePath, $filename, $tempname, $user_id) {
		$logger = Zend_Registry::get("logger");
		$photoUploadPath = $photoBasePath . "/" . $user_id;
		$toContinue = true;
		if (!is_dir($photoBasePath)) {
			if (!mkdir($photoBasePath)){
				$toContinue = false;
			}
		}
		if(!is_dir($photoUploadPath)) {
			if(!mkdir($photoUploadPath)) {
				$toContinue = false;
			}
		}

		if($toContinue == true) {
			//hash the filename with the timestamp. Get a unique url
			$bn = basename($filename);

			$a = explode(".", $bn);
			$ext = strtolower($a[1]);

			if($ext != 'jpg' && $ext != 'jpeg') {
				return false;
			}
			$h = md5(time() . $a[0]);
			$finalname = md5($h) . "." . $ext;
			$uploadFile = $photoUploadPath . "/" . $finalname;

			if(move_uploaded_file($tempname, $uploadFile)) {
				$result = true;
			}
			else {
				$result = false;
			}
		}
		else {
			$result = false;
		}

		return $result;
	}

}

?>