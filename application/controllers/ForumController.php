<?php

class ForumController extends Zend_Controller_Action
{

    public function init()
    {
	    throw new Zend_Acl_Exception;
    }

    public function indexAction(){
	    throw new Zend_Acl_Exception;
    }


}//end of class