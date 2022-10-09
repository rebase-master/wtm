<?php
//Thumbnail creation script for use with phototour
class Phototour_ExifReader
{

    protected $logger;

    public function __construct()
    {
        if (Zend_Registry::isRegistered('logger')) {
            $logger = Zend_Registry::get('logger');
            $this->logger = $logger;
        }
    }

    public function getExifData($imagePath)
    {
        $this->exifToolPath = EXIF_TOOL_PATH;        

        $exifCmd = $this->exifToolPath . " -X " . $imagePath;
        $outputString = exec($exifCmd, $outputArray, $exifToolReturn);

        if (($exifToolReturn == 0) && ($outputArray)) {
            $exifOutputString = implode($outputArray);
            $exifXml = simplexml_load_string($exifOutputString);
            $namespaces = $exifXml->getNameSpaces(true);
            $rdf = $exifXml->children($namespaces['rdf']);

            $description = $rdf->Description;
            $namespaces = $description->getNameSpaces(true);
            foreach ($namespaces as $key => $val) {
               // $this->logger->info($key);
            }
            //$this->logger->info("GPS set");

            $exif_array = exif_read_data($imagePath);
            if (isset($exif_array['GPSLatitude'])) {
                $latArr = $exif_array['GPSLatitude'];
                $lngArr = $exif_array['GPSLongitude'];
                $latRef = $exif_array['GPSLatitudeRef'];
                $lngRef = $exif_array['GPSLongitudeRef'];

                $latitude = (String)$this->convert2Degree($latArr);
                $longitude = (String)$this->convert2Degree($lngArr);

                if ($latRef == "S") {
                    $latitude = -$latitude;
                }
                if ($lngRef == "W") {
                    $longitude = -$longitude;
                }
            }
            else {
                $latitude = 0;
                $longitude = 0;
            }
            $file = $description->children($namespaces['File']);

            $width = $file->ImageWidth;
            $height = $file->ImageHeight;
        }
        else {
            $img = imagecreatefromjpeg($imagePath);
            if ($img) {
                $width = imagesx($img);
                $height = imagesy($img);
            }
            else {
                $width = 0;
                $height = 0;
            }
            $latitude = 0;
            $longitude = 0;
            $exifOutputString = "";
        }
        $params['width'] = $width;
        $params['height'] = $height;
        $params['latitude'] = $latitude;
        $params['longitude'] = $longitude;
        $params['exif_data'] = $exifOutputString;
        return $params;
    }


    public function convert2degree($arr)
    {
        $deg_str = $arr[0];
        $min_str = $arr[1];

        if (intval($min_str) > 1000) {
            $minArr = str_split($min_str, 2);
            $minNewStr = implode(".", $minArr);
            $minutes = floatval($minNewStr);

        }
        else {
            $minutes = floatval($min_str);
        }
        $finalVal = floatval($deg_str) + $minutes / 60;
        return $finalVal;
    }

}

?>