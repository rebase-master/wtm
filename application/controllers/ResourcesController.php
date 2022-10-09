<?php

class ResourcesController extends Zend_Controller_Action
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
       $this->_redirect('./tutorials');
    }

    public function tutorialsAction()
    {
	    $this->_redirect('./tutorials');
    }

	//people/:slug
    public function peopleAction()
    {
	    $this->_helper->_layout->setLayout('_2col');

	    $mdlNotes=new Model_Notes();
	    $mdlNotesContent = new Model_NotesContent();

	    if($this->getRequest()->getParam('slug')){
		    $slug = strip_tags($this->getRequest()->getParam('slug'));
		    $this->view->person = $t = $mdlNotesContent->findBySlug($slug, 1);
//		    var_dump($t);
//		    die;
	    }else{

		    if($this->getRequest()->getParam('poi') && $this->getRequest()->getParam('pid') )
		    {
			    $filter = new Zend_Filter_Int();
			    $id = $this->getRequest()->getParam('pid');
			    $this->view->person = $mdlNotes->findById($filter->filter($id));
		    }else{
			    $this->view->people = $mdlNotes->find('people');
		    }
	    }
    }

    public function installingAntAction(){
    }



	/**
	 * Redirected Routes here
	 */

	public function englishLanguageAction()
	{
		return $this->_redirect('/tutorials');
	}

	public function englishLiteratureAction()
	{
		return $this->_redirect('/tutorials');
	}


}//class







