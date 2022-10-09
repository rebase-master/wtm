<?php
//Geo calculations
class Phototour_Geo
{
    //Calculates Harvesine distance between two points
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371.0072;
        $lat1 = $lat1 * (M_PI / 180);
        $lon1 = $lon1 * (M_PI / 180);
        $lat2 = $lat2 * (M_PI / 180);
        $lon2 = $lon2 * (M_PI / 180);

        $diffLat = $lat2 - $lat1;
        $diffLon = $lon2 - $lon1;
        $a = sin($diffLat / 2) * sin($diffLat / 2) + cos($lat1) * cos($lat2) * sin($diffLon / 2) * sin($diffLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        return $d;
    }

    public function calculateDestination($centerLat, $centerLng, $bearing, $distance)
    {
        $R = 6371.0072;
        $d = $distance;
        $lat1 = $centerLat;
        $lon1 = $centerLng;

        $d = $distance;

        $lat1 = $lat1 * (M_PI / 180);
        $lon1 = $lon1 * (M_PI / 180);
        $theta = $bearing * (M_PI / 180);

        $lat2 = asin(sin($lat1) * cos($d / $R) + cos($lat1) * sin($d / $R) * cos($theta));
        $lon2 = $lon1 + atan2(sin($theta) * sin($d / $R) * cos($lat1), cos($d / $R) - sin($lat1) * sin($lat2));

        $c = array();
        $c['lat'] = $lat2 * (180 / M_PI);
        $c['lng'] = $lon2 * (180 / M_PI);

        return $c;
    }

    //Calculates the bounds given a radius in Kilometres
    public function calculateBounds($centerLat, $centerLng, $radius)
    {
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
}

?>