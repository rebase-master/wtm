<?php
class Pages_View_Helper_BaseUrl{
static function baseUrl(){
  //retrieves front controller
$fc=Zend_Controller_Front::getInstance();

//returns base URL value
return $fc->getBaseUrl();
}
}
