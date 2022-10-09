<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vikram
 * Date: 1/10/11
 * Time: 2:13 PM
 * To change this template use File | Settings | File Templates.
 */

class Phototour_Query
{

    public $db = "";

    public function is_assoc($arr)
    {
        return (is_array($arr) && count(array_filter(array_keys($arr), 'is_string')) == count($arr));
    }

    public function add($default, $param, $db, $update = 'id', $array = 0)
    {

        if ($update != 'id') {
            if ($array == 0)
                $update = "$update= values($update)";
            else
            {
                $update = "";
                foreach ($array as $vals)
                {
                    $update .= "$vals= values($vals),";
                }
                $update = substr($update, 0, -1);
            }
        }
        else
            $update = " ID=LAST_INSERT_ID(ID)";
        /*
$param =ARRAY(
0=>
Array(
'id' => 12,
'fid' => 1212,
'name' => "",
'place_id' => 0,
'street' => "",
'city' => "",
'country' => "",
'latitude' => 0,
'longitude' => 0
),

1=>
Array(
'id' => 12,
'fid' => 1212,
'name' => "",
'place_id' => 0,
'street' => "",
'city' => "",
'country' => "",
'latitude' => 0,
'longitude' => 0
));

$default =
Array(
'id' => NULL,
'fid' => 0,
'name' => "",
'place_id' => 0,
'street' => "",
'city' => "",
'country' => "",
'latitude' => 0,
'longitude' => 0
);                    */
        $params = "";
        if ($this->is_assoc($param)) {
            $data = array();
            $data[0] = $param;
            $params = $data;
        }
        else
            $params = $param;
        $query = "(";
        $fields = "";
        foreach ($params as $param)
        {

            $fields = "";
            $place = $param;
            foreach ($default as $val => $keys)
            {


                $fields .= "`$val`,";
                if (is_object($place) && (property_exists($place, $val)))
                    $value = $place->$val;
                elseif ((is_array($place)) && (array_key_exists($val, $place)))
                    $value = $place[$val];
                else
                    $value = $default[$val];

                if ($val == 'location')
                    $query .= "$value,";
                else
                    $query .= "'$value',";

            }
            $query = substr($query, 0, -1);
            $query .= "),(";

        }
        $data = new stdClass();
        $fields = "(" . substr($fields, 0, -1) . ")";
        $data->fields = $fields;
        $data->query = substr($query, 0, -2);
        if ($update == "")
            $query = "INSERT IGNORE INTO $db $data->fields VALUES $data->query";
        else
            $query = "INSERT INTO $db $data->fields VALUES $data->query ON DUPLICATE KEY UPDATE $update";
        $this->db->query($query);
        $this->id = $this->db->lastInsertId();
        return $query;


    }

}
