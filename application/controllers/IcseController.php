<?php

class ICSEController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('_3col');
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
	    $detect = new Mobile_Detect;
	    $this->view->isMobile = $detect->isMobile();
    }

    public function indexAction()
    {
        // action body
    }

    public function samplePapersAction()
    {
        // action body
    }

    public function previousYearsAction()
    {
        // action body
    }

	public function syllabusAction()
	{
		return $this->_redirect('/');
	}

}







