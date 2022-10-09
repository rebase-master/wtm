<?php
//Thumbnail creation script for use with phototour
class Phototour_Thumbnail
{

	public function __construct() {
		$this->dirPath = 'thumbs';
	}

	public function _generateThumb($imgPath, $thumbPath) {
		$tw = 64;
		$th = 64;

		$ch = curl_init($imgPath);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
		$cr = curl_exec($ch);
		curl_close($ch);

		$img = imagecreatefromstring($cr);
		$thumbImg = imagecreatetruecolor($tw, $th);
		$w = imagesx($img);
		$h = imagesy($img);

		if($w < $h) {
			$bw = $w;
			$bh = $w;
		}
		else {
			$bw = $h;
			$bh = $h;
		}

		imagecopyresampled($thumbImg, $img, 0, 0, 0, 0, $tw, $th, $bh, $bw);
		imagejpeg($thumbImg,$thumbPath);
	}

	public function getThumb($sourceUrl, $envId) {

		if (!is_dir($this->dirPath)) {
			if (!mkdir($this->dirPath)){
			return false;
			}
		}

		$modValue = (int)((int)$envId/1000);
		$thumbFolder = $this->dirPath.'/'.$modValue;


		if (!is_dir($thumbFolder)) {
			if (!mkdir($thumbFolder)){
			return false;
			}
		}

		$thumbUrl = $thumbFolder.'/thumb_'.$envId.'.jpg';

		if(!is_file($thumbUrl)) {
			$this->_generateThumb($sourceUrl, $thumbUrl);
		}
		return $thumbUrl;
	}

	public function createThumbFromImageFile($imgPath, $thumbPath, $imgWidth, $imgHeight, $width = 64, $height = 64) {

        if (($imgWidth == 0)||($imgHeight == 0)) {
            $imgArray = getimagesize($imgPath);
            $imgWidth = $imgArray[0];
            $imgHeight = $imgArray[1];
        }

        $maxDim = max(intval($width), intval($height));
        $asp_ratio = $imgWidth/$imgHeight;

        if ($maxDim == $height) {
            if ($asp_ratio*$height < $width) {
                $w = $width;
                $h = $width/$asp_ratio;
            }
            else {
                $w = $asp_ratio*$height;
                $h = $height;
            }

            $resCmd = "convert -auto-orient -resize ".$w."x$h $imgPath $thumbPath";
            $cropy = 0;
        }
        else {
            if ($width/$asp_ratio < $height) {
                $h = $height;
                $w = $height*$asp_ratio;
            }
            else {
                $w = $width;
                $h = $width/$asp_ratio;
            }
            $resCmd = "convert -auto-orient -resize $w.x".$h." $imgPath $thumbPath";
            $cropy = ($width/$asp_ratio - $height)/2;
        }
        $outputString = exec($resCmd, $outputArray, $ret);
        
        $cropCmd = "convert ".$thumbPath." -crop ".$width."x".$height."+0+".$cropy." ".$thumbPath;
        $cropOutput = exec($cropCmd, $outputArray, $ret);
		if($ret == 0) {

		}
		else {

		}
	}

    public function createThumbForPanorama($imgPath, $thumbPath, $imgWidth, $imgHeight, $width = 64, $height = 64) {
         if (($imgWidth == 0)||($imgHeight == 0)) {
            $imgArray = getimagesize($imgPath);
            $imgWidth = $imgArray[0];
            $imgHeight = $imgArray[1];
        }

        $maxDim = max(intval($width), intval($height));
        $asp_ratio = $imgWidth/$imgHeight;
        if ($maxDim == $height) {
            if ($asp_ratio*$height < $width) {
                $w = $width;
                $h = $width/$asp_ratio;
            }
            else {
                $w = $asp_ratio*$height;
                $h = $height;
            }

            $resCmd = IMAGEMAGICK_COMMAND." -auto-orient -resize ".$w."x$h $imgPath $thumbPath";
            $cropy = 0;
            $cropx = 0;
        }
        else {
            if ($width/$asp_ratio < $height) {
                $h = $height;
                $w = $height*$asp_ratio;
            }
            else {
                $w = $width;
                $h = $width/$asp_ratio;
            }
            $resCmd = IMAGEMAGICK_COMMAND." -auto-orient -resize ".$w."x".$h." $imgPath $thumbPath";
            $cropy = ($h - $height)/2;
            $cropx = ($w - $width)/2;
        }
        $outputString = exec($resCmd, $outputArray, $ret);
        $cropCmd = IMAGEMAGICK_COMMAND." ".$thumbPath." -crop ".$width."x".$height."+".$cropx."+".$cropy." ".$thumbPath;
        $cropOutput = exec($cropCmd, $outputArray, $ret);
		if($ret == 0) {

		}
		else {
		}
	}

    public function createUncroppedThumbFromImageFile($imgPath, $thumbPath, $imgWidth, $imgHeight, $width = 64, $height = 64) {
        $resCmd = IMAGEMAGICK_COMMAND . " -auto-orient -resize $width". "x" . "$height $imgPath $thumbPath";
		$outputString = exec($resCmd, $outputArray, $ret);
		if($ret == 0) {

		}
		else {

		}
    }

	public function createThumbFromRemoteImageFile($imgPath, $thumbPath, $width = 64, $height = 64) {
        // Creates a thumbnail from a remote image file

		$img = imagecreatefromjpeg($imgPath);
		$w = imagesx($img);
		$h = imagesy($img);
        $tw = $width;
        $th = $height;
        $minDim = min(intval($w),intval($h));
        if ($height < $width) {
            $bw= $minDim;
            $bh = $bw/($width/$height);
        }
        else {
            $bh= $minDim;
            $bw = $bh*($width/$height);
        }
        $thumbImg = imagecreatetruecolor($tw, $th);
        imagecopyresampled($thumbImg, $img, 0, 0, 0, 0, $tw, $th, $bw, $bh);
        imagejpeg($thumbImg,$thumbPath);
	}

    /*
    public function createHeightCroppedThumbFromPanorama($imgPath, $thumbPath, $width, $height) {
        $imgSize = getimagesize($imgPath);
        $asp_ratio = $imgSize[0]/$imgSize[1];

        // Resize the image to have same width as 
        $resCmd = IMAGEMAGICK_COMMAND . " -auto-orient -resize $width". "x" . "$height $imgPath $thumbPath";
        $outputString = exec($resCmd, $outputArray, $ret);
    }
     */

    public function createEnvironmentThumb($envId, $xSize, $ySize, $photoPath = 0, $basepath = "") {
        // Given an environment ID, create a thumb in a envID specific folder inside env_thumbs
        // The name of the thumbnail is suffixed with _width_height and is used for easy loading

        //For now salt is deadline
        $salt = "deadline";
        //Set up the path of the thumbnail
        $h = md5($envId . $salt) . "_" . $xSize . "_" . $ySize . ".jpg";
        $folder = $envId % 10000;

        $thumbFolder = $basepath . "env_thumbs/$folder";
        $thumbPath = $basepath . "env_thumbs/$folder/$h";

        if (!is_dir($thumbFolder)){
            mkdir($thumbFolder);
        }

        $newThumbFolder = $basepath . "thumbs/" . $envId . "/";
        $newThumbPath = $newThumbFolder . $xSize . "_" . $ySize . ".jpg";

        if(!is_dir($newThumbFolder)) {
            mkdir($newThumbFolder);
        }

        echo $photoPath . "\n";
        //See if photo path is available
        if ($photoPath) {
            //Create thumb of specified dimenstions
            $imgSize = getimagesize($photoPath);

            //Old thumb path
            $this->createThumbForPanorama($photoPath, $thumbPath, $imgSize[0], $imgSize[1], $xSize, $ySize);
            //New thumb path
            $this->createThumbForPanorama($photoPath, $newThumbPath, $imgSize[0], $imgSize[1], $xSize, $ySize);
        }
    }

    public static function getAbsoluteUrl($environment_id, $user_id, $width, $height) {
        $salt = 'deadline';
        $ext = 'jpg';
        $h = $environment_id . $salt;
        $folder = $environment_id % 10000;
        $finalname = md5($h) . "_" . $width . "_" . $height . "." .  $ext;

        $photoBasePath = 'env_thumbs' . "/" . $folder;

        $fullPath = $photoBasePath . "/" . $finalname;

        $config = Zend_Registry::get("config");
        $apiUrl = $config->urls->apiUrl;

        $absUrl = $apiUrl . $fullPath;
        return $absUrl;
    }

    /*
    *   Create a circular thumbnail using imagemagick of the source image.
    */
    public function createCircularThumb($imgPath, $thumbPath, $imgWidth, $imgHeight, $width = 64, $height = 64) {      

        // Image width and height should be specified
        if (($imgWidth == 0)||($imgHeight == 0)) {
            return false;
        }

        // Circular thumbnail must hace equal x and y dimensions
        if ($width != $height) {
            $tempdim = min($width,$height);
            $width = $tempdim;
            $height = $tempdim;
        }

        $mindim =   min($imgWidth,$imgHeight);
        /*
        $scale = round($mindim/$width,0);
        if ($scale == 0) {
            $scale = 1;
        }
        */        

        // Create circular image and resize to 64x64
        $resCmd =   IMAGEMAGICK_COMMAND . " -size $mindim". "x" . "$mindim xc:none -fill $imgPath -draw \"circle " .
                    $mindim/2 . "," . $mindim/2 . ",1," . $mindim/2 . "\" -resize $width". "x" . "$height $thumbPath";
                                
        $outputString = exec($resCmd, $outputArray, $ret);

        if($ret == 0) {
            return false;
        }
        else {
            return true;
        }        

    } 

    public function getFacebookImage($openGraphUrl, $thumbPath){
    
        $img = file_get_contents($openGraphUrl);

        if (!$img) {
            return false;
        }
        
        $success = file_put_contents($thumbPath, $img);

        if (!$success) {
            return false;
        }

        $sizeArr = getimagesize($thumbPath);

        if(!$sizeArr){
            return false;
        }

        $success = $this->createCircularThumb($thumbPath,$thumbPath,$sizeArr[0],$sizeArr[1],64,64);

        return $success;
    
    }
}

?>