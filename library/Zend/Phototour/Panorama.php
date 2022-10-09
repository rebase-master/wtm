<?php
class Phototour_Panorama {
	protected $logger;
	protected $currDir;
	protected $siteUrl;
	protected  $maxPanoramaWidth = 2800;
	
	public function __construct() {
		if(Zend_Registry::isRegistered('logger')) {
				$this->logger = Zend_Registry::get('logger');
		}
        $this->siteUrl = API_PHOTOTOUR;
		$this->currDir = getcwd();
		$this->currDir = str_replace('\\','/',$this->currDir);
		$this->logger->info($this->currDir);
	}
		
	private function shred($location) {
		// shred uses image magick now 12/20/2010

        $fail = false;
		$this->logger->info($this->siteUrl);
        //ImageMagick cropping operation
        $output = exec ("identify ".$location, $outputArr, $return_var);
        if ($return_var == 0) {
            $sizeArray = explode(" ",$output);
            $imageSize = explode("x",$sizeArray[2]);
            $imagex = $imageSize[0];
            $imagey = $imageSize[1];
        }
        else {
            $fail = true;
        }


		$tempWidth = $imagex;
		$height = $imagey;
		$curX = 0;
		$panOrder = 1;
		$parts = array();
		
		while(($tempWidth > 0) && ($fail == false))	{
            $tempLoc =  substr($location,0,strlen($location)-4) . '_' . $panOrder . '.jpg';
			if($tempWidth > 2800)	{
                $resize = exec("convert ".$location." -crop 2800x".$height."+".$curX."+0 ".$tempLoc, $outputArr, $return_var);

                if ($return_var != 0) {
                    $fail = true;
                }
                $this->logger->info("image copied");
				$tempWidth -= 2800;
				$curX += 2800;
			}
			else {
                $resize = exec("convert ".$location." -crop ".$tempWidth."x".$height."+".$curX."+0 ".$tempLoc, $outputArr, $return_var);
                if ($return_var != 0) {
                    $fail = true;
                }
				$tempWidth = 0;
			}	
			$panOrder++;
		}
		
		$this->numBaseParts = $panOrder;
        return $fail;
	}
	
	
	private function imagetograyscale($location) {
        // Uses imagemagick
        $grayLoc =  substr($location,0,strlen($location)-4) . '_gray.jpg';
        $gray = exec("convert ".$location." -type Grayscale ".$grayLoc, $outputArr, $return_var);
        if ($return_var == 0) {
            return $grayLoc;
        }
        else {
            return false;
        }
	}
	
	public function processPanorama($imagePath) {
         // If grayscale needed
        /*
        $grayLoc = $this->imagetograyscale($imagePath);
        if($grayLoc) {
            $grayShred = $this->shred($grayLoc);
        }
         */
        $shred = $this->shred($imagePath);
    }


    public function generateSkyBoxTiles($panoramaPath, $fov) {
        //Create the folder
        $tileBasePath =  substr($panoramaPath,0,strlen($panoramaPath)-4);
        if (!is_dir($tileBasePath)) {
            mkdir($tileBasePath);
        }
        $tileHighPath = $tileBasePath."/tiles_highres";
        if (!is_dir($tileHighPath)) {
            mkdir($tileHighPath);
        }
        //Try to execute the tile generation executable
        $exec_path = "skyBox";
        $commandString = "$exec_path $panoramaPath $fov $tileBasePath 512";
        $commandHighString = "$exec_path $panoramaPath $fov $tileHighPath 1000";
        $execCommandString = "./".$commandString;

        Phototour_Logger::log($execCommandString);

        try {
            exec($execCommandString, $outputArr, $output);
            if (!is_file($tileBasePath."/tile_right.jpg")) {
                Phototour_Logger::log("Try with slash");
                $execCommandString = "./$exec_path $panoramaPath $fov $tileBasePath/ 512";
                exec($execCommandString,$outputArr,$output);
            }
            exec("./".$commandHighString,$outputArr,$output);

            //Write in tile_lowres
            $lowResPath = $tileBasePath."/tile_lowres";
            if(!is_dir($lowResPath)) {
                mkdir($lowResPath);
            }
            $lowResCommand = "./$exec_path $panoramaPath $fov $lowResPath 512";
            exec($lowResCommand,$outputArr, $output);
        }
        catch (Exception $e) {
            Phototour_Logger::log("Exception ".$e->getMessage());
        }

        if ($output != 0) {
            Phototour_Logger::log("Not successful ".$output);
        }
        else {
            Phototour_Logger::log("Succesfully executed");
        }
    }

    public function padPanorama($panoramaPath, $fov) {
        $fovInRadians = $fov*(pi()/180);

        // Set the sizes forinput and output images
        Phototour_Logger::log("FOV is ".$fov);
        $inputImage = imagecreatefromjpeg($panoramaPath);
        $inputWidth = imagesx($inputImage);
        $inputHeight = imagesy($inputImage);
        $panoramicWidth = (360/$fov)*$inputWidth;
        $panoramicImage = imagecreatetruecolor($panoramicWidth,$inputHeight);
        
        // Copy relevant portion of input imageand keep  the rest black
        $black = imagecolorallocate($panoramicImage,0,0,0);
        imagefill($panoramicImage,0,0,$black);
        imagecopy($panoramicImage,$inputImage,0,0,0,0,$inputWidth,$inputHeight);

        //Set the output path
        $path360 =  substr($panoramaPath,0,strlen($panoramaPath)-4);
        if (!is_dir($path360)) {
            mkdir($path360);
        }
        $path360 .= "/360.jpg";
        imagejpeg($panoramicImage,$path360);

        //Flip the image horizontally using imagemagick and save
        $flipCmd = IMAGEMAGICK_COMMAND . " $path360 -flop $path360";
        $outputString = exec($flipCmd, $outputArr, $ret);
        if ($ret != 0)
            $this->logger->info("Could not flip image");
    }

}