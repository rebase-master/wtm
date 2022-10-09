<?php

class GalleryController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
	    return $this->_redirect('/page-under-construction');
    }


}//end of class