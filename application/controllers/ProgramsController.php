<?php

class ProgramsController extends Zend_Controller_Action
{

    public function init()
    {
       $this->_helper->_layout->setLayout('_programs');
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
	    $detect = new Mobile_Detect;
	    $this->view->isMobile = $detect->isMobile();
    }

	//URL: program/:slug
    public function indexAction()
    {
	    if($this->getRequest()->getParam('slug')){
		    $slug = strip_tags($this->getRequest()->getParam('slug'));

		    $mdlPrograms            =   new Model_Programs();
		    $mdlProgramComments     =   new Model_ProgramComments();
		    $program                =   $mdlPrograms->findBySlug($slug);
//		    var_dump($program);
//		    die;
		    if(!empty($program)){
			    $this->view->relatedPrograms  =   $mdlPrograms->relatedPrograms($program->topic_id);
			    $this->view->program          =   $program;
			    $this->view->comments         =   $mdlProgramComments->getComments(intval($program->id));
			    $this->view->slug             =   $slug;
		    }else{
			    throw new Zend_Acl_Exception;
		    }
	    }else{

		    throw new Zend_Acl_Exception;
	    }

    }

	//URL: category/:slug
    public function categoryAction()
    {
	    if($this->getRequest()->getParam('slug')){
		    $slug = strip_tags($this->getRequest()->getParam('slug'));
		    $category = ucwords(str_replace('-',' ',$slug));
		    $mdlPrograms = new Model_Programs();
		    $programs    = $mdlPrograms->getByTopic($slug);

		    if(count($programs) > 0){

			    $this->view->meta =
				    array(
					    'name' => $category." Questions",
					    'description' => 'List of solved '.$category.' questions meticulously prepared by experts.',
					    'image' => $slug.'.png',
					    'title' => $category." Questions",
					    'url' => BASE_URL.'category/'.$slug
				    );

			    $this->view->category = $category;

			    $filterInt  = new Zend_Filter_Int();

			    if(!empty($programs)) {
				    $currentPage = 1;
				    $i = $this->_request->getQuery('page');
				    $i = $filterInt->filter($i);
				    if (!empty($i)) {
					    $currentPage = $this->_request->getQuery('page');
				    }

				    $paginator = Zend_Paginator::factory($programs);

				    $this->view->limit = $limit = 10;
				    $paginator->setItemCountPerPage($limit);
				    $paginator->setPageRange(ceil(count($programs) / $limit));
				    $paginator->setCurrentPageNumber($currentPage);
				    $this->view->paginator = $paginator;
				    $this->view->pageRange = count($programs);
				    $this->view->currentPage = $currentPage;
			    }
		    }

	    }else{
		    throw new Zend_Acl_Exception;
	    }

    }

	//isc/:subject/:type/:slug
    public function yearlyQuestionAction()
    {
	    if($this->getRequest()->getParam('subject')  && $this->getRequest()->getParam('type')){

		    $subject = strip_tags($this->getRequest()->getParam('subject'));
		    $type    = strip_tags($this->getRequest()->getParam('type'));
		    $slug    = strip_tags($this->getRequest()->getParam('slug'));


		    if(empty($subject) || empty($type))
			    throw new Zend_Acl_Exception;


		    if($subject == 'solved-isc-computer-practical' && $type == 'year' && intval($slug)>= 2000){
			    return $this->_redirect('isc/isc-computer-practical#/'.intval($slug).'/Q1');
		    }

		    if(empty($slug))
			    return $this->_forward('yearly-questions', 'programs','default', array('subject' => $subject, 'type' => $type));

		    $mdlYearlyQuestions     =   new Model_YearlyQuestions();
		    $program                =   $mdlYearlyQuestions->findOneBySlug($subject, $type, $slug);

		    if(empty($program))
			    throw new Zend_Acl_Exception;
		    else{
			    $subjectName = ucwords(str_replace('-',' ', $subject));
			    $typeName    = ucwords($type);

			    $this->view->program    =   $program;
			    $this->view->meta =
				    array(
					    'name' => !empty($program['heading'])?$program['heading']: 'ISC '.$subjectName.' '.$typeName. ' Question',
					    'description' => substr(strip_tags($program['question']), 0, 140).'...',
					    'image' => 'default.png',
					    'title' => !empty($program['heading'])?$program['heading']: 'ISC '.$subjectName.' '.$typeName. ' Question',
					    'url' => BASE_URL.'isc/'.$subject.'/'.$type.'/'.$slug
				    );
		    }

	    }else{

		    if($this->getRequest()->getParam('subject')){
			    $subject = strip_tags($this->getRequest()->getParam('subject'));

			    if(in_array($subject, array(
				    'isc-computer-guess-questions',
				    'computer-project',
				    'isc-comp-find-output-type-ques',
				    'isc-comp-theory-sample-ques',
				    'isc-computer-practical',
				    'previous-years',
				    'sample-papers',
				    'viva-voce',
				    'yearly',
				    'agpqbyyear'
			    )))
				    return $this->_forward($subject, 'isc','default');
		    }

		    throw new Zend_Acl_Exception;
	    }

    }


    public function yearlyQuestionsAction()
    {
	    if($this->getRequest()->getParam('subject')  && $this->getRequest()->getParam('type')){

		    $this->view->subject = $subject =  strip_tags($this->getRequest()->getParam('subject'));
		    $this->view->type    = $type    =  strip_tags($this->getRequest()->getParam('type'));

		    if(empty($subject) || empty($type))
			    throw new Zend_Acl_Exception;

		    $mdlYearlyQuestions =   new Model_YearlyQuestions();
		    $programs           =   $mdlYearlyQuestions->getYearlyQuestion($type, null, $subject);
//		    echo count($program);
//		    die;
		    if(count($programs) == 0)
			    throw new Zend_Acl_Exception;
		    else{
			    $filterInt  = new Zend_Filter_Int();
			    $subjectName = ucwords(str_replace('-',' ', $subject));
			    $typeName = ucwords($type);

			    $this->view->meta =
				    array(
					    'name'        => 'ISC '.$subjectName.' '.$typeName. ' Questions',
					    'description' => 'List of '.count($programs).' '.$typeName. ' Questions for ISC '.$subjectName,
					    'image'       => 'default.png',
					    'title'       => 'ISC '.$subjectName.' '.$typeName. ' Questions',
					    'url'         => BASE_URL.'isc/'.$subject.'/'.$type
				    );

			    $currentPage = 1;
			    $i = $this->_request->getQuery('page');
			    $i = $filterInt->filter($i);
			    if (!empty($i)) {
				    $currentPage = $this->_request->getQuery('page');
			    }

			    $paginator = Zend_Paginator::factory($programs);

			    $this->view->limit = $limit = 10;
			    $paginator->setItemCountPerPage($limit);
			    $paginator->setPageRange(ceil(count($programs) / $limit));
			    $paginator->setCurrentPageNumber($currentPage);
			    $this->view->paginator = $paginator;
			    $this->view->pageRange = count($programs);
			    $this->view->currentPage = $currentPage;

		    }

	    }else{
		    throw new Zend_Acl_Exception;
	    }

    }


	public function ajaxAddCommentAction()
	{
		if(Zend_Auth::getInstance()->hasIdentity()){
			$identity = Zend_Auth::getInstance()->getIdentity();

			if($this->getRequest()->isXmlHttpRequest()){
				$data         = $this->getRequest()->getParams();
				$data['uid']  = $identity->id;

				$mdlProgramComments = new Model_ProgramComments();

				if($mdlProgramComments->createComment($data))
					echo json_encode(array(
						'status'    => 1,
						'username'  => $identity->username,
						'comment'   => strip_tags($data['comment'])
					));
				else
					echo json_encode(array(
						'status' => -1
					));
				exit;
			}
		}

		throw new Zend_Acl_Exception;
	}



	/**
	 * Redirected routes here
	 */

	public function questionsAction(){
		return $this->_redirect('/qa');
	}

	public function askQuestionAction()
	{
		return $this->_redirect('/qa/ask-question');
	}

}





