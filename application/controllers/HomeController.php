<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('_home');
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
	    $detect = new Mobile_Detect;
	    $this->view->isMobile = $detect->isMobile();
    }

    public function indexAction()
    {
	    $filter = new Zend_Filter_Int();

	    $mdlPrograms            =   new Model_Programs();
	    $mdlNotes               =   new Model_Notes();
	    $mdlNotesContent        =   new Model_NotesContent();
	    $mdlYearlyQuestions     =   new Model_YearlyQuestions();
	    $mdlRiddles             =   new Model_Riddles();

	    $stringQuestions        =   $mdlPrograms->getByTopic(array('strings-basic'), 5);
	    $arrayQuestions         =   $mdlPrograms->getByTopic(array('arrays-1d'), 5);
	    $seriesQuestions        =   $mdlPrograms->getByTopic(array('series'), 5);
	    $sortingQuestions       =   $mdlPrograms->getByTopic(array('sorting'), 3);
	    $searchingQuestions     =   $mdlPrograms->getByTopic(array('searching'), 5);

	    $guessCSQuestions       =   $mdlYearlyQuestions->getYearlyQuestions('guess', null, 'computer science', 5);
	    $practicalCSQuestions   =   $mdlYearlyQuestions->getYearlyQuestions('practical', date('m') > 2 ? date('Y'):date('Y')-1, 'computer science', 3);
//	    $computerNotes          =   $mdlNotes->getRandomNote();
	    $notes                  =   $mdlNotesContent->homeStream();
//	    $engLitNote             =   $mdlNotes->getRandomNote('English Literature');
//	    $engLangNote            =   $mdlNotes->getRandomNote('English Language');
//	    $chemNote               =   $mdlNotes->getRandomNote('chemistry');
//	    var_dump($engLitNote);
//	    die;

	    $this->view->questions = array(
		    'array'                  =>  $arrayQuestions,
		    'strings'                =>  $stringQuestions,
		    'series'                 =>  $seriesQuestions,
		    'sorting'                =>  $sortingQuestions,
		    'search'                 =>  $searchingQuestions,
		    'cs_guess_questions1'     =>  $guessCSQuestions,
		    'cs_practical_questions' =>  $practicalCSQuestions
	    );

	    if (count($notes) > 0){
		    $currentPage = 1;
		    $i = $this->_request->getQuery('page');
		    $i = $filter->filter($i);
		    if (!empty($i)) {
			    $currentPage = $i;
		    }
		    $this->view->limit = $limit = 10;
		    $paginator = Zend_Paginator::factory($notes);
		    $paginator->setItemCountPerPage($limit);
		    $paginator->setPageRange(ceil(count($notes) / $limit));
		    $paginator->setCurrentPageNumber($currentPage);
		    $this->view->paginator = $paginator;
		    $this->view->pageRange = count($notes);
		    $this->view->currentPage = $currentPage;
	    }
    }

    public function privacyPolicyAction(){
        $this->_helper->layout()->setLayout('_nocol');
    }

    public function termsOfUseAction(){
        $this->_helper->layout()->setLayout('_nocol');
    }





	/**
	 * Redirected Routes here
	 */

	public function trialClassAction()
	{
		return $this->_redirect('/');
	}

	public function coachingResultsAction()
	{
		return $this->_redirect('/');
	}

	public function icseResultsAction()
	{
		return $this->_redirect('/');
	}

	public function iscResultsAction()
	{
		return $this->_redirect('/');
	}

	public function sitemapAction(){
		return $this->_redirect('/');
	}

}





