<?php

class VideosController extends Zend_Controller_Action
{

    public function init()
    {
       $this->_helper->layout->setLayout('_2col');
    }

    public function indexAction()
    {
//       $this->_helper->layout->setLayout('videos');
		$filterInt=new Zend_Filter_Int();
        $mdlVideos=new Model_Videos();
        $video=$mdlVideos->allVideos();
        if($video){
            $currentPage=1;
            $i=$this->_request->getQuery('page');
            $i=$filterInt->filter($i);
            if(!empty($i)){
                $currentPage=$this->_request->getQuery('page');
            }
            $paginator=Zend_Paginator::factory($video);
            
            //set the properties for pagination
            $paginator->setItemCountPerPage(1);
            $paginator->setPageRange(ceil(count($video)/1));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator=$paginator;
            $this->view->pageRange=count($video);
            $this->view->currentPage=$currentPage;
    }

    }

    public function showAction()
    {
        $mdlVideos=new Model_Videos();
        $video=$mdlVideos->randomVideo();
        if($video)
            $this->view->video=$video;
        else{
            $this->view->error="Video not found";
        }
    }


}









