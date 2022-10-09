<?php
$mdlUser=new Model_User();
if (Zend_Auth::getInstance()->hasIdentity()) {
    $identity = Zend_Auth::getInstance()->getIdentity();
    $username=$identity->username;
    $loggedinuserid = $identity->id;
}else{
    $username = "";
    $identity="";
    $loggedinuserid = 0;
}
?>