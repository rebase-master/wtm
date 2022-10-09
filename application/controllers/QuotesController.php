<?php

class QuotesController extends Zend_Controller_Action
{

    public function init()
    {
	    $this->_helper->_layout->setLayout('_2col');
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
	    $detect = new Mobile_Detect;
	    $this->view->isMobile = $detect->isMobile();
    }

    public function indexAction()
    {
        $mdlQuotes = new Model_Quotes();
        $filter = new Zend_Filter_Int();
        $quotes = $mdlQuotes->listQuotes();

        if($quotes){
            $currentPage=1;
            $i=$this->_request->getQuery('page');
            $i=$filter->filter($i);
            if(!empty($i)){
                $currentPage=$i;
            }
            $recordsPerPage = 15;
            $paginator=Zend_Paginator::factory($quotes);
            $paginator->setItemCountPerPage($recordsPerPage);
            $paginator->setPageRange(ceil(count($quotes)/$recordsPerPage));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator=$paginator;
            $this->view->pageRange=count($quotes);
            $this->view->currentPage=$currentPage;
        }
    }

	//URL: quote/:slug
    public function findBySlugAction()
    {

	    $slug = urldecode($this->getRequest()->getParam('slug'));

	    if(!empty($slug)){
		    $mdlQuotes = new Model_Quotes();
		    $this->view->quote        =  $mdlQuotes->findOneBySlug($slug);
		    $this->view->randomQuotes =  $mdlQuotes->randomQuotes();
	    }else{
		    throw new Zend_Acl_Exception;
	    }

    }

}











