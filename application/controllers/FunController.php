<?php

class FunController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('_nocol');
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
	    $detect = new Mobile_Detect;
	    $this->view->isMobile = $detect->isMobile();
    }

    public function indexAction()
    {
	    return $this->_redirect('/page-under-construction');

	    $this->_helper->_layout->setLayout('_2col');
        if(!Zend_Auth::getInstance()->hasIdentity()){
        $this->view->visitor=0;    
        }else{
            $this->view->visitor=1;    
        }
    }

    public function riddlesAction()
    {
        $this->_helper->_layout->setLayout('_2col');
        $mdlRiddles = new Model_Riddles();
        $riddles = $mdlRiddles->get();
        $filterInt=new Zend_Filter_Int();
        if($riddles){
            $currentPage=1;
            $i=$this->_request->getQuery('page');
            $i=$filterInt->filter($i);
            if(!empty($i)){
                $currentPage=$this->_request->getQuery('page');
            }
            $paginator=Zend_Paginator::factory($riddles);
            
            //set the properties for pagination
            $paginator->setItemCountPerPage(10);
            $paginator->setPageRange(ceil(count($riddles)/10));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator=$paginator;
            $this->view->pageRange=count($riddles);
            $this->view->currentPage=$currentPage;
        }
    }

    public function triviaAction()
    {
        $this->_helper->_layout->setLayout('_2col');

        $mdlTrivia=new Model_Trivia();
        $trivia=$mdlTrivia->readTrivia();
        $filter=new Zend_Filter_Int();
        if($trivia){
            $currentPage=1;
            $i=$this->_request->getQuery('page');
            $i=$filter->filter($i);
            if(!empty($i)){
                $currentPage=$i;
            }
            $paginator=Zend_Paginator::factory($trivia);
            $paginator->setItemCountPerPage(10);
            $paginator->setPageRange(ceil(count($trivia)/10));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator=$paginator;
            $this->view->pageRange=count($trivia);
            $this->view->currentPage=$currentPage;
        }
	}

	public function funFactsAction(){
		return $this->_redirect('/trivia');
	}

	public function brainteasersAction(){
		return $this->_redirect('/riddles');
	}





	public function javaQuizAction(){
		return $this->_redirect('/page-under-construction');
	}


}//end of class