<?php
//Thumbnail creation script for use with phototour
class Phototour_Stream
{

    public function __construct()
    {
        if (Zend_Registry::isRegistered('logger')) {
            $logger = Zend_Registry::get('logger');
            $this->logger = $logger;
        }
    }

    public function getUSStateFromAbbr($abbr)
    {
        $map = array();
        $map['AK'] = 'Alaska';

        $map['AK'] = 'Alabama';
        $map['AS'] = 'American Samoa';
        $map['AZ'] = 'Arizona';
        $map['AR'] = 'Arkansas';
        $map['CA'] = 'California';
        $map['CO'] = 'Colorado';
        $map['CT'] = 'Connecticut';
        $map['DE'] = 'Delaware';
        $map['DC'] = 'District of Columbia';
        $map['FM'] = 'Federated States of Micronesia';
        $map['FL'] = 'Florida';
        $map['GA'] = 'Georgia';
        $map['GU'] = 'Guam';
        $map['HI'] = 'Hawaii';
        $map['ID'] = 'Idaho';
        $map['IL'] = 'Illinois';
        $map['IN'] = 'Indiana';
        $map['IA'] = 'Iowa';
        $map['KS'] = 'Kansas';
        $map['KY'] = 'Kentucky';
        $map['LA'] = 'Louisiana';
        $map['ME'] = 'Maine';
        $map['MH'] = 'Marshall Islands';
        $map['MD'] = 'Maryland';
        $map['MA'] = 'Massachusetts';
        $map['MI'] = 'Michigan';
        $map['MN'] = 'Minnesota';
        $map['MS'] = 'Mississippi';
        $map['MO'] = 'Missouri';
        $map['MT'] = 'Montana';
        $map['NE'] = 'Nebraska';
        $map['NV'] = 'Nevada';
        $map['NH'] = 'New Hampshire';
        $map['NJ'] = 'New Jersey';
        $map['NM'] = 'New Mexico';
        $map['NY'] = 'New York';
        $map['NC'] = 'North Carolina';
        $map['ND'] = 'North Dakota';
        $map['MP'] = 'Northern Mariana Islands';
        $map['OH'] = 'Ohio';
        $map['OK'] = 'Oklahoma';
        $map['OR'] = 'Oregon';
        $map['PW'] = 'Palau';
        $map['PA'] = 'Pennsylvania';
        $map['PR'] = 'Puerto Rico';
        $map['RI'] = 'Rhode Island';
        $map['SC'] = 'South Carolina';
        $map['SD'] = 'South Dakota';
        $map['TN'] = 'Tennessee';
        $map['TX'] = 'Texas';
        $map['UT'] = 'Utah';
        $map['VT'] = 'Vermont';
        $map['VI'] = 'Virgin Islands';
        $map['VA'] = 'Virginia';
        $map['WA'] = 'Washington';
        $map['WV'] = 'West Virginia';
        $map['WI'] = 'Wisconsin';
        $map['WY'] = 'Wyoming';
        $map['AE'] = 'Armed Forces Africa';
        $map['AA'] = 'Armed Forces Americas (except Canada)';
        $map['AE'] = 'Armed Forces Canada';
        $map['AE'] = 'Armed Forces Europe';
        $map['AE'] = 'Armed Forces Middle East';
        $map['AP'] = 'Armed Forces Pacific';

        if (isset($map[$abbr])) {
            return $map[$abbr];
        } else {
            return false;
        }
    }

    public function getRegion($address)
    {
        if ($address != "" && $address != null) {
            $a = explode(',', $address);
            $num = count($a);

            if ($num > 1) {
                $start = $num - 2;
                $terms = array();

                if (trim($a[$num - 1]) == 'USA') {
                    $abbr = trim($a[$num - 2]);

                    $b = explode(" ", $abbr);
                    $terms[0] = $this->getUSStateFromAbbr(trim($b[0]));
                    if ($terms[0] == false) {
                        $terms[0] = $abbr;
                    }
                    $terms[1] = 'USA';
                } else {
                    //Remove all numbers
                    $regex = "/[0-9]+/i";
                    for ($i = $start; $i < $num; $i++) {
                        $a[$i] = preg_replace($regex, "", $a[$i]);
                        if ($a[$i] != null || $a[$i] != "") {
                            $terms[] = trim($a[$i]);
                        }
                    }
                }

                return implode(", ", $terms);
            } else {
                return $address;
            }
        } else {
            return null;
        }
    }

    public function formatRegion($address)
    {
        $region = strtolower($address);
        $region = str_replace(" ", "_", $region);
        return $region;
    }

    public function getDisplayAddress($address, $where)
    {
        if (empty($where)) {
            $region = $this->getRegion($address);
        } else {
            $region = $this->getRegion($address);

            if (empty($region)) {
                $region = $where;
            } else {

                //@todo A fix when address is the same as photo_where
                $region = ($region == $where) ? $region : $where . ", " . $region;
            }
        }

        return $region;
    }

}

?>