<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
//	    $this->_helper->layout()->disableLayout();
//	    $this->_helper->viewRenderer->setNoRender(true);

	    $this->_helper->_layout->setLayout('admin');
        $mdlUser            = new Model_User();
        $mdlUserData        = new Model_UserData();
        $mdlQuotes          = new Model_Quotes();
        $mdlVideos          = new Model_Videos();
        $mdlJQQ             = new Model_JavaQuizQuestions();
        $mdlJQS             = new Model_JavaQuizScores();
        $mdlFacts           = new Model_Trivia();
	    $mdlQuizQuestions   = new Model_QuizQuestions();
	    $mdlRiddles         = new Model_Riddles();
	    $mdlPrograms        = new Model_Programs();
	    $mdlNotes           = new Model_Notes();
	    $mdlSubject         = new Model_Subjects();
	    $mdlTopic           = new Model_Topics();

        $this->view->usersCount         = $mdlUser->getUserCount();
        $this->view->verified           = $mdlUser->getUnverifiedUsersCount();
        $this->view->female             = $mdlUser->getFemaleCount();
        $this->view->male               = $mdlUser->getMaleCount();
        $this->view->dps                = $mdlUserData->getNoOfDps();
        $this->view->quotesCount        = $mdlQuotes->countQuotes();
        $this->view->triviaCount        = $mdlFacts->countTrivia();
        $this->view->videosCount        = $mdlVideos->countVideos();
        $this->view->javaBeg            = $mdlJQQ->quesInLevel('beginner');
        $this->view->javaInter          = $mdlJQQ->quesInLevel('intermediate');
        $this->view->javaAd             = $mdlJQQ->quesInLevel('advanced');
        $this->view->begAttempts        = $mdlJQS->quizTakersByCategory('beginner');
        $this->view->interAttempts      = $mdlJQS->quizTakersByCategory('intermediate');
        $this->view->adAttempts         = $mdlJQS->quizTakersByCategory('advanced');
	    $this->view->quizCount          = $mdlQuizQuestions->count();
	    $this->view->riddlesCount       = $mdlRiddles->count();
	    $this->view->programsCount      = $mdlPrograms->count();
	    $this->view->notesCount         = $mdlNotes->count();
	    $this->view->subjectsCount      = $mdlSubject->count();
	    $this->view->topicsCount        = $mdlTopic->count();

    }

    public function indexAction()
    {

    }

	public function analyticsAction(){
		$mdlDownloads = new Model_Downloads();
		$downloads = $mdlDownloads->groupDownloads();
		$filter = new Zend_Filter_Int();
		if(!empty($downloads)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator = Zend_Paginator::factory($downloads);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($downloads)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($downloads);
			$this->view->currentPage = $currentPage;
		}
	}

	public function getUsersByYearAction(){
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$year = ($this->getRequest()->getParam('year'));
		$mdlUser  = new Model_User();
		$users = $mdlUser->getUsersByYear($year);
		echo json_encode($users);
		exit;
	}

	public function getEnquiriesAction()
	{
		$mdlContact  = new Model_Contact();
		$enquiries = $mdlContact->getEnquiries();
		$this->view->enquiries = $enquiries;

	}

	public function readUsersAction(){
        $mdlUser  = new Model_User();
        $users = $mdlUser->getThemAll();
        $filterInt  = new Zend_Filter_Int();
	    if(!empty($users)){
            $this->view->result=1;
	        $currentPage=1;
	        $i = $this->_request->getQuery('page');
	        $i = $filterInt->filter($i);
	        if(!empty($i)){
	            $currentPage = $this->_request->getQuery('page');
	        }
	        $paginator=Zend_Paginator::factory($users);

		    $this->view->limit = $limit = 30;
		    $paginator->setItemCountPerPage($limit);
	        $paginator->setPageRange(ceil(count($users)/$limit));
	        $paginator->setCurrentPageNumber($currentPage);
	        $this->view->paginator = $paginator;
	        $this->view->pageRange=count($users);
	        $this->view->currentPage = $currentPage;
        }
    }

	public function blockUserAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if ($this->getRequest()->isXmlHttpRequest()) {
			$uid  = intval($this->getRequest()->getParam('uid'));
			$mode = $this->getRequest()->getParam('mode') == 'unblock'? 0: 1;

			if (Zend_Auth::getInstance()->hasIdentity()) {
				$identity = Zend_Auth::getInstance()->getIdentity();

				$mdlUser = new Model_User();
				$result = $mdlUser->blockUser($uid, $mode);

				if($result){
					echo json_encode(array(
						'status' => 1
					));
				}else{
					echo json_encode(array(
						'status' => -1
					));
				}
			}
			exit;
		}
		echo json_encode(array(
			'status' => -1
		));
	}

	public function verifyUserAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if ($this->getRequest()->isXmlHttpRequest()) {
			$uid  = intval($this->getRequest()->getParam('uid'));
			$mode = $this->getRequest()->getParam('mode') == 'unblock'? 0: 1;

			if (Zend_Auth::getInstance()->hasIdentity()) {
				$identity = Zend_Auth::getInstance()->getIdentity();

				$mdlUser = new Model_User();
				$result = $mdlUser->verfiyUser($uid, $mode);

				if($result){
					echo json_encode(array(
						'status' => 1
					));
				}else{
					echo json_encode(array(
						'status' => -1
					));
				}
			}
			exit;
		}
		echo json_encode(array(
			'status' => -1
		));
	}

    public function readUnverifiedUsersAction(){
        $mdlUser  = new Model_User();
        $users = $mdlUser->getUnverifiedUsers();
        $filterInt  = new Zend_Filter_Int();

	    if(!empty($users)){
		    $currentPage = 1;
	        $i = $this->_request->getQuery('page');
	        $i = $filterInt->filter($i);
	        if(!empty($i)){
	            $currentPage = $this->_request->getQuery('page');
	        }
	        $paginator=Zend_Paginator::factory($users);

		    $this->view->limit = $limit = 30;
		    $paginator->setItemCountPerPage($limit);
	        $paginator->setPageRange(ceil(count($users)/$limit));
	        $paginator->setCurrentPageNumber($currentPage);
	        $this->view->paginator = $paginator;
	        $this->view->pageRange=count($users);
	        $this->view->currentPage = $currentPage;
	    }
    }

    public function readJavaQuizParticipantsAction()
    {
        $mdlJavaQuizScores  = new Model_JavaQuizScores();
        $quizParticipants = $mdlJavaQuizScores->quizTakers();
        $filter = new Zend_Filter_Int();
	    if(!empty($quizParticipants)){
            $currentPage = 1;
            $i = $this->_request->getQuery('page');
            $i = $filter->filter($i);
            if(!empty($i)){
                $currentPage = $i;
            }
            $this->view->limit = $limit = 30;
            $paginator=Zend_Paginator::factory($quizParticipants);
            $paginator->setItemCountPerPage($limit);
            $paginator->setPageRange(ceil(count($quizParticipants)/$limit));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator = $paginator;
            $this->view->pageRange=count($quizParticipants);
            $this->view->currentPage = $currentPage;
        }
    }

    public function readQuizParticipantsAction(){
        $mdlQuizScores  = new Model_QuizScores();
	    $quizParticipants = $mdlQuizScores->quizTakers();
        $filter  = new Zend_Filter_Int();
        if(!empty($quizParticipants)){
            $currentPage = 1;
            $i = $this->_request->getQuery('page');
            $i = $filter->filter($i);
            if(!empty($i)){
                $currentPage = $i;
            }
            $this->view->limit = $limit = 30;
            $paginator=Zend_Paginator::factory($quizParticipants);
            $paginator->setItemCountPerPage($limit);
            $paginator->setPageRange(ceil(count($quizParticipants)/$limit));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator = $paginator;
            $this->view->pageRange=count($quizParticipants);
            $this->view->currentPage = $currentPage;
        }
    }

    public function readNoOfQuestionsAction()
    {
        $mdlQuizQuestions  = new Model_QuizQuestions();
        $mdlQuizCategory  = new Model_QuizCategory();
        $categories = $mdlQuizCategory->getCategories();
        $catQues=array();
        $i=0;
        foreach($categories as $category){
            $results = $mdlQuizQuestions->countQuesByCategory($category->category);
            $catQues[$i][0] = $category->category;
            $catQues[$i][1] = $results;
            $i++;
        }
        $this->view->catQues = $catQues;

    }


//Java Question CRUD

	public function createJavaQuestionAction(){
		if(Zend_Auth::getInstance()->hasIdentity()){
			$form = new Form_JavaQuestions();
			if($this->_request->isPost() && $form->isValid($this->getRequest()->getPost())){
				$data = $form->getValues();
				$level=htmlentities(strip_tags($data['level']));
				//echo $level; die;
				$question=(($data['question']));
				$answer=(strip_tags($data['answer']));
				$mdlJavaQuizQuestions = new Model_JavaQuizQuestions();
				$id = $mdlJavaQuizQuestions->add($level,$question,$answer);
				if($id!=null){
					$option1=($form->getValue('option1'));
					$option2=($form->getValue('option2'));
					$option3=($form->getValue('option3'));
					$option4=($form->getValue('option4'));

					$mdlJavaQuizOptions = new Model_JavaQuizOptions();
					$options=array($option1,$option2,$option3,$option4);
					$mdlJavaQuizOptions->add($id,$options);
					$form->reset();
					$this->view->result="<p>Question added successfully.</p><br />";
				}else{
					$this->view->result="<p>Could not add question at the moment.</p><br />";
				}
			}
			$this->view->form = $form;
		}
	}

	public function readJavaQuestionsAction(){
		$mdlJavaQuizQuestions = new Model_JavaQuizQuestions();
		$questions = $mdlJavaQuizQuestions->getAllQuestions();
		$filter = new Zend_Filter_Int();
		if(!empty($questions)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($questions);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($questions)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($questions);
			$this->view->currentPage = $currentPage;
		}
	}

    public function updateJavaQuestionAction(){
        $filter = new Zend_Filter_Int();
        if($this->getRequest()->getParam('id')){
            $id = $filter->filter($this->getRequest()->getParam('id'));
            $form = new Form_JavaQuestions();
            $mdlJavaQuizQuestions = new Model_JavaQuizQuestions();
            $mdlJavaQuizOptions = new Model_JavaQuizOptions();
            $question = $mdlJavaQuizQuestions->getJavaQuestion($id);
            $options = $mdlJavaQuizOptions->getOptionsByQues($id);
            $form->populate($question->toArray());
            $i=1;
            foreach($options as $option){
                $form->getElement('option'.$i++)->setValue($option['options']);
            }
            if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())){
                $data = $form->getValues();
                $level=htmlentities(strip_tags($data['level']));
                $question=(($data['question']));
                $answer=htmlentities(strip_tags($data['answer']));
                $mdlJavaQuizQuestions = new Model_JavaQuizQuestions();
                $mdlJavaQuizQuestions->updateQuestion($id,$level,$question,$answer);

                $option1=($form->getValue('option1'));
                $option2=($form->getValue('option2'));
                $option3=($form->getValue('option3'));
                $option4=($form->getValue('option4'));

                $mdlJavaQuizOptions = new Model_JavaQuizOptions();
                $options=array($option1,$option2,$option3,$option4);
                $mdlJavaQuizOptions->updateOptions($id,$options);
                $this->view->result="<p>Question updated successfully.</p><br />";
            }

            $this->view->form = $form;
        }
    }

	public function deleteJavaQuestionAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter  = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$mdlJavaQuizQuestions  = new Model_JavaQuizQuestions();
			$mdlJavaQuizOptions  = new Model_JavaQuizOptions();
			try{
				$mdlJavaQuizQuestions->deleteQuestion($id);
				$mdlJavaQuizOptions->deleteOptions($id);
				echo 1;
			}catch(Exception $e){
				echo -1;
			}
			exit;
		}
	}
//END Java Question CRUD

//Riddles CRUD
    public function createRiddleAction()
    {
        $form  = new Form_Riddles();
        if($this->_request->isPost() && $form->isValid($_POST)){
            $riddle=htmlspecialchars($form->getValue('riddle'));
            $answer=htmlspecialchars($form->getValue('answer'));
            $mdlRiddles  = new Model_Riddles();
            $result = $mdlRiddles->save($riddle,$answer);
            if($result)
                $this->view->message="Riddle added successfully";
            else
                $this->view->message="Riddle could not be added at the moment. Please try again in sometime";
            $form->reset();
        }
        $this->view->form = $form;
    }

	public function readRiddlesAction()
	{
		$mdlRiddles  = new Model_Riddles();
		$riddles = $mdlRiddles->listRiddles();
		$filter  = new Zend_Filter_Int();
		if(!empty($riddles)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($riddles);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($riddles)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($riddles);
			$this->view->currentPage = $currentPage;
		}
	}

	public function updateRiddleAction(){
		$filter  = new Zend_Filter_Int();
		$id = $filter->filter($this->getRequest()->getParam('id'));
		$mdlRiddles = new Model_Riddles();
		$this->view->record = $mdlRiddles->findById($id);
	}

	public function editRiddleAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter  = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$riddle = $this->getRequest()->getParam('riddle');
			$answer = $this->getRequest()->getParam('answer');
			$mdlRiddles = new Model_Riddles();
			if($mdlRiddles->updateRiddle($id, $riddle,$answer))
				echo 1;
			else
				echo -1;
			exit;
		}
	}

	public function deleteRiddleAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter  = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$mdlRiddles = new Model_Riddles();
			if($mdlRiddles->removeRiddle($id))
				echo 1;
			else
				echo -1;
			exit;
		}
	}
//End Riddles CRUD

//Trivia CRUD
    public function createTriviaAction()
    {
        if($this->getRequest()->isXmlHttpRequest()){
            $trivia = $this->getRequest()->getParam('trivia');
            $mdlTrivia  = new Model_Trivia();
	        $trivia = htmlspecialchars($trivia);
            echo $mdlTrivia->createTrivia($trivia)? 1 : -1;
            exit;
        }
    }

	public function readTriviaAction()
	{
		$mdlTrivia  = new Model_Trivia();
		$Trivia = $mdlTrivia->readTrivia();
		$filter  = new Zend_Filter_Int();
		if(!empty($Trivia)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($Trivia);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($Trivia)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($Trivia);
			$this->view->currentPage = $currentPage;
		}
	}

    public function updateTriviaAction(){
	    $filter  = new Zend_Filter_Int();
	    $id = $filter->filter($this->getRequest()->getParam('id'));
        $mdlTrivia  = new Model_Trivia();
        $this->view->record = $mdlTrivia->findById($id);
    }

    public function editTriviaAction(){
        if($this->getRequest()->isXmlHttpRequest()){
	        $filter  = new Zend_Filter_Int();
	        $id = $filter->filter($this->getRequest()->getParam('id'));
            $trivia = $this->getRequest()->getParam('trivia');
            $mdlTrivia = new Model_Trivia();
            echo $mdlTrivia->updateTrivia($id, $trivia) ? 1:-1;
            exit;
        }
    }

	public function deleteTriviaAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter  = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$mdlTrivia = new Model_Trivia();
			echo $mdlTrivia->deleteTrivia($id) ? 1:-1;
			exit;
		}
	}
//END Trivia CRUD


//Programs CRUD
    public function createProgramAction()
    {
        $frmProgram = new Form_Programs();

        if($this->getRequest()->isPost()){

            if($frmProgram->isValid($_POST)){

                $data = $frmProgram->getValues();
                $mdlPrograms = new Model_Programs();

	            if($mdlPrograms->createProgram($data)){
		            $status = "Program added successfully.";
		            $frmProgram->reset();
	            }else{
		            $status = "Something went wrong!";
	            }

                $this->view->success = $status;

            }
        }

        $frmProgram->setAction('./create-program');

        $this->view->form = $frmProgram;
    }

	public function readProgramsAction()
	{
		$mdlPrograms = new Model_Programs();
		$programs = $mdlPrograms->listPrograms();
		$filterInt  = new Zend_Filter_Int();

		if(!empty($programs)) {
			$currentPage = 1;
			$i = $this->_request->getQuery('page');
			$i = $filterInt->filter($i);
			if (!empty($i)) {
				$currentPage = $this->_request->getQuery('page');
			}

			$paginator = Zend_Paginator::factory($programs);

			$this->view->limit = $limit = 30;
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($programs) / $limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange = count($programs);
			$this->view->currentPage = $currentPage;
		}
	}

	public function updateProgramAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));
		$mdlProgram = new Model_Programs();
		$frmPrograms = new Form_Programs();
		$program = $mdlProgram->findById($id);

		if(!empty($program))
			$frmPrograms->populate($program->toArray());

		$this->view->form    = $frmPrograms;
		$this->view->program = $program;

		if($this->getRequest()->isPost()){
			if($frmPrograms->isValid($_POST)){
				$mdlProgram->updateProgram($id, $frmPrograms->getValues());
				$this->_redirect('./admin/read-programs');
			}
		}

	}

	public function deleteProgramAction()
    {
	    $filter = new Zend_Filter_Int();
	    $id = $filter->filter($this->_request->getParam('id'));
        $mdlPrograms = new Model_Programs();
        $mdlPrograms->deleteProgram($id);
        $this->_redirect('./admin/read-programs');
    }
//END Programs CRUD


//Yearly Question CRUD
	public function createYearlyQuestionAction(){

		if($this->getRequest()->isPost()){

			$data = $this->getRequest()->getParams();
			$mdlYearlyQuestion = new Model_YearlyQuestions();
			$mdlYearlyQuestionTopics = new Model_YearlyQuestionTopics();
//			var_dump($data);
//			die;
			$id = $mdlYearlyQuestion->createQuestion($data);

			if(!empty($id))
				$mdlYearlyQuestionTopics->createQuestionTopics($id, $data['topics']);

			$this->_redirect('./admin/read-yearly-questions');
		}

		$mdlSubjects = new Model_Subjects();
		$mdlTopics = new Model_Topics();
		$this->view->topics = $mdlTopics->readTopics();
		$this->view->subjects = $mdlSubjects->readSubjects();
	}

	public function readYearlyQuestionsAction(){

		$mdlYearlyQuestions = new Model_YearlyQuestions();

		if($this->getRequest()->getParam('type')){
			$type = strip_tags($this->getRequest()->getParam('type'));
		}

		if($this->getRequest()->getParam('year')){
			$year = strip_tags($this->getRequest()->getParam('year'));
		}

		if(isset($type) && isset($year)){

			$questions = $mdlYearlyQuestions->getYearlyQuestions($type, $year);

		}else if(isset($year)){

			$questions = $mdlYearlyQuestions->getYearlyQuestions(null, $year);

		}else if(isset($type)){

			$questions = $mdlYearlyQuestions->getYearlyQuestions($type, null);

		}else{

			$questions = $mdlYearlyQuestions->readAllQuestions();

		}

		$filterInt  = new Zend_Filter_Int();

		if(!empty($questions)) {
			$currentPage = 1;
			$i = $this->_request->getQuery('page');
			$i = $filterInt->filter($i);
			if (!empty($i)) {
				$currentPage = $this->_request->getQuery('page');
			}

			$limit = 30;

			$mdlYearlyQuestionTopics = new Model_YearlyQuestionTopics();

			$questions = $questions->toArray();

			for($j = 0; $j < count($questions); $j++){
				$questions[$j]['topics'] =	$mdlYearlyQuestionTopics->getTopicsByQuestionId($questions[$j]['id']);
			}
			$paginator = Zend_Paginator::factory($questions);

			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($questions) / $limit));
			$paginator->setCurrentPageNumber($currentPage);

			$this->view->limit          =   $limit;
			$this->view->paginator      =   $paginator;
			$this->view->pageRange      =   count($questions);
			$this->view->currentPage    =   $currentPage;
			$this->view->totalCount     =   count($questions);
		}
	}

	public function updateYearlyQuestionAction()
    {
	    $filter = new Zend_Filter_Int();
	    $id = $filter->filter($this->_request->getParam('id'));

	    $mdlYearlyQuestions         =   new Model_YearlyQuestions();
	    $mdlYearlyQuestionTopics    =   new Model_YearlyQuestionTopics();
	    $mdlSubjects                =   new Model_Subjects();
	    $mdlTopics                  =   new Model_Topics();

	    $question                   =   $mdlYearlyQuestions->findById($id);
		$question                   =   $question->toArray();
	    $question['topics']         =	$mdlYearlyQuestionTopics->getTopicsByQuestionId($question['id']);

	    $this->view->topics         =   $mdlTopics->readTopics();
	    $this->view->subjects       =   $mdlSubjects->readSubjects();
		$this->view->record         =   $question;

        if($this->getRequest()->isPost()){
            $paramId = intval($this->getRequest()->getParam('id'));
	        $data = $this->getRequest()->getParams();
            $id = $mdlYearlyQuestions->updateQuestion($paramId, $data);
	        if(!empty($id) && isset($data['topics']))
		        $mdlYearlyQuestionTopics->updateQuestionTopics($id, $data['topics']);
	        $this->_redirect('./admin/read-yearly-questions');
        }

	}

	public function deleteYearlyQuestionAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));
		$mdlGuessQuestion = new Model_YearlyQuestions();
		$mdlGuessQuestion->removeQuestion($id);
		$this->_redirect('./admin/read-yearly-questions');
	}
//END Yearly Question CRUD

//Videos CRUD
	public function createVideoAction()
    {
        $frmVideos = new Form_Videos();
        if($this->_request->isPost()){
            if($frmVideos->isValid($_POST)){
                $data = $frmVideos->getValues();
                $mdlVideos = new Model_Videos();
                $result = $mdlVideos->addVideo($data);
                if($result){
                    $this->view->success="Video added successfully.";
                    $frmVideos->reset();
                }
            }
        }
        $frmVideos->setAction('./admin/add-video');
        $this->view->form = $frmVideos;
    }

	public function readVideosAction()
	{
		$mdlVideos = new Model_Videos();
		$videos = $mdlVideos->allVideos();

		$filterInt  = new Zend_Filter_Int();
		if(!empty($videos)) {
			$this->view->result = 1;
			$currentPage = 1;
			$i = $this->_request->getQuery('page');
			$i = $filterInt->filter($i);
			if (!empty($i)) {
				$currentPage = $this->_request->getQuery('page');
			}
			$paginator = Zend_Paginator::factory($videos);

			$this->view->limit = $limit = 30;
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($videos) / $limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange = count($videos);
			$this->view->currentPage = $currentPage;
		}
	}

	public function updateVideoAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));
		$mdlVideos = new Model_Videos();
		$frmVideos = new Form_Videos();
		$video = $mdlVideos->findById($id);

		if($this->_request->isPost() && $frmVideos->isValid($this->getRequest()->getPost())){
			$mdlVideos->updateVideo($id,$frmVideos->getValues());
			$this->_redirect('./admin/list-videos');
		}
		if($video!=null){
			$frmVideos->populate($video->toArray());
		}
		$this->view->form = $frmVideos;
	}

	public function deleteVideoAction()
    {
        $filter = new Zend_Filter_Int();
        $id = $filter->filter($this->_request->getParam('id'));
        $mdlVideos = new Model_Videos();
        $mdlVideos->deleteVideo($id);
        $this->_redirect('./admin/list-videos');
    }
//END Videos CRUD


//Quotes CRUD
	public function createQuoteAction()
	{
		if($this->getRequest()->isXmlHttpRequest()){
			$mdlQuotes = new Model_Quotes();
			echo $mdlQuotes->addQuote($this->getRequest()->getParams()) ? 1 : -1;
			exit;
		}
	}

	public function readQuotesAction()
	{
		$mdlQuotes = new Model_Quotes();
		$quotes = $mdlQuotes->listQuotes();

		$filter = new Zend_Filter_Int();
		if(!empty($quotes)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($quotes);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($quotes)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($quotes);
			$this->view->currentPage = $currentPage;
		}

	}

	public function updateQuoteAction()
	{
		$filter  = new Zend_Filter_Int();
		$id = $filter->filter($this->getRequest()->getParam('id'));
		$mdlQuotes = new Model_Quotes();
		$this->view->record = $mdlQuotes->findById($id);
	}

	public function editQuoteAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter  = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$mdlQuotes = new Model_Quotes();
			echo $mdlQuotes->updateQuote($id, $this->getRequest()->getParams()) ? 1 : -1;
			exit;
		}
	}

	public function deleteQuoteAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter  = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$mdlQuotes = new Model_Quotes();
			if($mdlQuotes->deleteQuote($id))
				echo 1;
			else
				echo -1;
			exit;
		}
	}
//END Quotes CRUD


//Subjects CRUD
	public function createSubjectAction()
	{
		if($this->_request->isPost()){

			$mdlSubjects = new Model_Subjects();
			$result = $mdlSubjects->createSubject($_POST);

			if(!empty($result)){
				$this->view->msg="Record added successfully.";
			}else{
				$this->view->msg="Record could not be added.";
			}
		}
	}

	public function readSubjectsAction()
	{
		$mdlSubjects = new Model_Subjects();
		$subjects = $mdlSubjects->readSubjects();
		$filter = new Zend_Filter_Int();

		if(!empty($subjects)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($subjects);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($subjects)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($subjects);
			$this->view->currentPage = $currentPage;
		}

	}

	public function updateSubjectAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));
		$mdlSubjects = new Model_Subjects();

		$this->view->record = $mdlSubjects->findById($id);
	}

	public function editSubjectAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
//			var_dump($this->_request->getParams());
//			exit;
			$mdlSubjects = new Model_Subjects();
			echo $mdlSubjects->updateSubject($id, $this->_request->getParams()) ? 1 : -1;
			exit;
		}
	}

	public function deleteSubjectAction()
	{
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			$mdlSubjects = new Model_Subjects();

			echo $mdlSubjects->removeSubject($id) ? 1 : -1;

			exit;
		}
	}
//END Subjects CRUD

//Topic CRUD
	public function createTopicAction()
	{
		if($this->_request->isPost()){

			$mdlTopics = new Model_Topics();
			$result = $mdlTopics->createTopic($_POST);

			if(!empty($result)){
				$this->view->msg="Record added successfully.";
			}else{
				$this->view->msg="Record could not be added.";
			}
		}
	}

	public function readTopicsAction()
	{
		$mdlTopics = new Model_Topics();
		$topics = $mdlTopics->readTopics();
		$filter = new Zend_Filter_Int();

		if(!empty($topics)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($topics);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($topics)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($topics);
			$this->view->currentPage = $currentPage;
		}

	}

	public function updateTopicAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));
		$mdlTopics = new Model_Topics();

		$this->view->record = $mdlTopics->findById($id);
	}

	public function editTopicAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			$mdlTopics = new Model_Topics();
			echo $mdlTopics->updateTopic($id, $this->_request->getParams()) ? 1 : -1;
			exit;
		}
	}

	public function deleteTopicAction()
	{
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			$mdlTopics = new Model_Topics();

			echo $mdlTopics->removeTopic($id) ? 1 : -1;

			exit;
		}
	}
//END Topic CRUD

//Notes Category CRUD
	public function createNotesCategoryAction()
	{
		if($this->_request->isPost()){
			$mdlNotesCategory=new Model_NotesCategory();
			$category = strip_tags($_POST['category']);
			$type = strip_tags($_POST['type']);
			$result = $mdlNotesCategory->addCategory($category, $type);
			if(!empty($result)){
				$this->view->msg="Record added successfully.";
			}else{
				$this->view->msg="Record could not be added.";
			}
		}
	}

	public function readNotesCategoryAction()
	{
		$mdlNotes=new Model_NotesCategory();
		$categories = $mdlNotes->readCategories();
		$filter = new Zend_Filter_Int();
		if(!empty($categories)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($categories);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($categories)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($categories);
			$this->view->currentPage = $currentPage;
		}

	}

	public function updateNotesCategoryAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));
		$mdlNotesCategory = new Model_NotesCategory();

		$this->view->record = $mdlNotesCategory->findById($id);
	}

	public function editNotesCategoryAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			$category = strip_tags($this->_request->getParam('category'));
			$type = strip_tags($this->_request->getParam('type'));

			$mdlNotesCategory = new Model_NotesCategory();
			if($mdlNotesCategory->updateNotesCategory($id, $category, $type)){
				echo 1;
			}else{
				echo -1;
			}
			exit;
		}
	}

	public function deleteNotesCategoryAction()
	{
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			$mdlNotesCategory = new Model_NotesCategory();

			if($mdlNotesCategory->removeNoteCategory($id))
				echo 1;
			else
				echo -1;
			exit;
		}
	}
//END Notes CRUD


//Notes CRUD
	public function createNoteAction()
	{
		$frmNotes = new Form_Notes();

		if($this->getRequest()->isPost()) {
			if ($frmNotes->isValid($_POST)) {
				$mdlNotes = new Model_Notes();
				$data = $frmNotes->getValues();
				$result = $mdlNotes->add($data);

				if (!empty($result)) {
					$this->view->message = "Resource added successfully.";
					$frmNotes->reset();
				} else {
					$this->view->message = "Resource could not be added.";
				}

			} else {
				$this->view->message = "Errors in the form.";
			}
		}

		$frmNotes->setAction('./create-note');
		$this->view->form = $frmNotes;
	}

	public function readNotesAction()
	{

		$mdlNotes=new Model_Notes();

		if($this->getRequest()->getParam('category')){
			$category = strtolower(strip_tags(urldecode($this->getRequest()->getParam('category'))));
			$notes = $mdlNotes->listNotes($category);
		}else{
			$notes = $mdlNotes->listNotes();
		}

		$filter = new Zend_Filter_Int();
		if(!empty($notes)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($notes);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($notes)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange = count($notes);
			$this->view->currentPage = $currentPage;
		}
		$mdlcategory = new Model_NotesCategory();
		$this->view->categories = $mdlcategory->readCategories();

	}

//	TODO: Fix this for AJAX
	public function updateNoteAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));

		if($id != 0){

			$mdlNotes = new Model_Notes();
			$frmNotes = new Form_Notes();

			$note = $mdlNotes->findById($id);

			if($this->getRequest()->isPost()){
				if($frmNotes->isValid($_POST)){
						$mdlNotes->updateNotes($id, $frmNotes->getValues());
						$this->_redirect('./admin/read-notes');
				}//form valid
			}//POST

			$frmNotes->populate($note->toArray());
			$this->view->form  = $frmNotes;
			$this->view->note = $note;

		}else{
			throw new Zend_Acl_Exception;
		}

	}

//	TODO: Fix this for AJAX
	public function deleteNoteAction()
	{
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->getRequest()->getParam('id'));
			$mdlNotes = new Model_Notes();
			if($mdlNotes->removeNote($id))
				echo 1;
			else
				echo -1;
			exit;
		}
	}
//END Notes CRUD

//Notes Content CRUD
	public function createNotesContentAction()
	{
		$frmNotesContent = new Form_NotesContent();

		if($this->getRequest()->isPost()){
			if ($frmNotesContent->isValid($_POST)) {
				$mdlNotesContent = new Model_NotesContent();
				$upload = new Zend_File_Transfer_Adapter_Http();
				$filename = $upload->getFilename();

				if(!empty($filename)){

					$filename = $upload->getFilename();
					$extension = substr(strrchr(basename($filename), '.'), 1);
					$filename = md5(basename($filename));


					$uniqueToken = md5(uniqid(mt_rand(), true));
					$coverImage = $uniqueToken."_".$filename.".".$extension;
					$filterRename = new Zend_Filter_File_Rename(
						array(
							'target' => APPLICATION_PATH.'/../public/images/content/uploads/' . $coverImage,
							'overwrite' => false
						)
					);

					$upload->addFilter($filterRename);

					if ($upload->receive()) {
						$data = $frmNotesContent->getValues();
						$data['cover_image'] = $coverImage;
						$result = $mdlNotesContent->add($data);

						if (!empty($result)) {
							$this->view->message = "Resource added successfully.";
							$frmNotesContent->reset();
						} else {
							$this->view->message = "Resource could not be added.";
						}
					}else{
						$this->view->message = 'Error receiving the file';

					}
				}else{
					$data   =   $frmNotesContent->getValues();
					$result =   $mdlNotesContent->add($data);

					if (!empty($result)) {
						$this->view->message = "Resource added successfully.";
						$frmNotesContent->reset();
					} else {
						$this->view->message = "Resource could not be added.";
					}
				}

			} else {
				$this->view->message = "Errors in the form.";
			}

//			die;
		}

		$this->view->form = $frmNotesContent;
		$frmNotesContent->setAction('./create-notes-content');
	}

	public function readNotesContentAction()
	{
		$mdlNotesContent = new Model_NotesContent();

		if($this->getRequest()->getParam('sub_category')){
			$subCategory = strtolower(strip_tags(urldecode($this->getRequest()->getParam('sub_category'))));
			$notes = $mdlNotesContent->listNotes($subCategory);
		}
		else if($this->getRequest()->getParam('type')){
			$type = strtolower(strip_tags(urldecode($this->getRequest()->getParam('type'))));
			$notes = $mdlNotesContent->listNotes(null, $type);
		}
		else{
			$notes = $mdlNotesContent->listNotes();
		}

		$filter = new Zend_Filter_Int();

		if(!empty($notes)){
			$currentPage=1;
			$i = $this->_request->getQuery('page');
			$i = $filter->filter($i);
			if(!empty($i)){
				$currentPage = $i;
			}
			$this->view->limit = $limit = 30;
			$paginator=Zend_Paginator::factory($notes);
			$paginator->setItemCountPerPage($limit);
			$paginator->setPageRange(ceil(count($notes)/$limit));
			$paginator->setCurrentPageNumber($currentPage);
			$this->view->paginator = $paginator;
			$this->view->pageRange=count($notes);
			$this->view->currentPage = $currentPage;
		}
		$mdlNotes = new Model_Notes();
		$this->view->notes = $mdlNotes->getSubCategories();

	}

//	TODO: Fix this for AJAX
	public function updateNotesContentAction()
	{
		$filter = new Zend_Filter_Int();
		$id = $filter->filter($this->_request->getParam('id'));

		if($id != 0) {
			$mdlNotesContent = new Model_NotesContent();
			$frmNotesContent = new Form_NotesContent();

			$notesContent = $mdlNotesContent->findById($id);
//			var_dump($notesContent);
//			die;
			if ($this->getRequest()->isPost()) {
				if ($frmNotesContent->isValid($_POST)) {

					$fileChanged = $this->getRequest()->getParam('file_changed');

//					echo "fc: ".intval($fileChanged);
//					var_dump($notesContent);
//					die;

					if(intval($fileChanged) == 1) {

						if (!empty($notesContent->cover_image) && file_exists(APPLICATION_PATH . '/../public/images/content/uploads/' . $notesContent->cover_image)) {
							unlink(APPLICATION_PATH . '/../public/images/content/uploads/' . $notesContent->cover_image);
						}

						$upload = new Zend_File_Transfer_Adapter_Http();
						$filename = $upload->getFilename();
						$extension = substr(strrchr(basename($filename), '.'), 1);
						$filename = md5(basename($filename));


						$uniqueToken = md5(uniqid(mt_rand(), true));
						$coverImage = $uniqueToken."_".$filename.".".$extension;
//						echo "cover: ".$coverImage;
//						die;
						$filterRename = new Zend_Filter_File_Rename(
							array(
								'target' => APPLICATION_PATH . '/../public/images/content/uploads/' . $coverImage,
								'overwrite' => false
							)
						);
						$upload->addFilter($filterRename);

						if ($upload->receive()) {
							$data = $frmNotesContent->getValues();
							$data['cover_image'] = $coverImage;
							$mdlNotesContent->updateNotes($id, $data);
							$this->_redirect('./admin/read-notes-content');
						} else {
							$this->view->message = "Unable to receive file.";
						}
					}else{
						$mdlNotesContent->updateNotes($id, $frmNotesContent->getValues());
						$this->_redirect('./admin/read-notes-content');
					}
				}else{
//					var_dump($frmNotesContent->getValues());

					$this->view->message = "Error(s) in form.";
//					var_dump($frmNotesContent->getErrors());
//					var_dump($frmNotesContent->getErrorMessages());
//					die;
				}
			}//POST

			$frmNotesContent->populate($notesContent->toArray());
			$this->view->form = $frmNotesContent;
			$this->view->notesContent = $notesContent;

		}else{
			throw new Zend_Acl_Exception;
		}
	}

	public function editNotesContentAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			exit;
		}
	}

//	TODO: Fix this for AJAX
	public function deleteNotesContentAction()
	{
		if($this->getRequest()->isXmlHttpRequest()){
			$filter = new Zend_Filter_Int();
			$id = $filter->filter($this->_request->getParam('id'));
			$mdlNotes = new Model_NotesContent();
			if($mdlNotes->removeNote($id))
				echo 1;
			else
				echo -1;
			exit;
		}
	}
//END Notes Content CRUD


	public function createQuizAction()
	{
		$form  = new Form_QuizQuestions();
		if($this->_request->isPost() && $form->isValid($_POST)){
			$category = $form->getValue('category');
			$mdlQuizCategory  = new Model_QuizCategory();
			$id = $mdlQuizCategory->findId($category);
			$question = $form->getValue('question');
			$answer = $form->getValue('answer');
			$mdlQuizQuestions  = new Model_QuizQuestions();
			$result = $mdlQuizQuestions->addQuestion($id,$question,$answer);

			$option1 = $form->getValue('option1');
			$option2 = $form->getValue('option2');
			$option3 = $form->getValue('option3');
			$option4 = $form->getValue('option4');

			$mdlQuizOptions  = new Model_QuizOptions();
			$options=array($option1,$option2,$option3,$option4);
			$mdlQuizOptions->addOptions($result,$options);
			$form->reset();
		}
		$this->view->form = $form;
	}


}