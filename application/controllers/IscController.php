<?php

class ISCController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('_3col');
	    $this->_helper->contextSwitch()
		     ->addActionContext('agpqbyyear', 'json')
		     ->initContext('json');
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

    public function iscComputerPracticalAction()
    {
        $this->_helper->_layout->setLayout('_2col_angular');

        if(Zend_Auth::getInstance()->hasIdentity()){
            $this->view->verified = 1;
        }

	    if($this->getRequest()->getParam('v')){

		    return $this->_redirect('/isc/isc-computer-guess-questions');
	    }else{

            $mdlYearlyQuestions = new Model_YearlyQuestions();
            $questions = $mdlYearlyQuestions->getPracticalQuestions();
		    $groupedQuestions = $this->groupQuestionsByYear($questions);
		    krsort($groupedQuestions);
		    $this->view->practicalQuestions = $groupedQuestions;
	    }

    }

	public function iscComputerGuessQuestionsAction(){
		$this->_helper->_layout->setLayout('_2col_angular');
		$mdlGuessQuestion = new Model_YearlyQuestions();
		$this->view->guessQuestions = $q = $mdlGuessQuestion->getYearlyQuestions('guess');
	}

	public function yearlyAction(){

		$this->_helper->layout()->disableLayout();
//		$this->renderScript('isc/yearly.html');
	}

    public function vivaVoceAction()
    {
    }
    public function solvedIscComputerPracticalAction(){
        $name=$this->_request->getParam('year');
        $filter=new Zend_Filter_Int();
        $name=$filter->filter($name);
        $mdlTopics=new Model_Topics();
        $id=$mdlTopics->getTopicIdByName($name);
        if($id!=-1 && $name!=0){
            $mdlPrograms=new Model_Programs();
            $programs=$mdlPrograms->listPrograms($id);
            $this->view->programs=$programs;
            $this->view->year=$name;

        }else{
            $this->view->error=1;
        }

    }
    public function computerProjectAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity()){
            $identity=Zend_Auth::getInstance()->getIdentity();
            $this->view->uid = $identity->id;
            $this->view->loggedIn=true;
        }else{
            $this->view->loggedIn=false;
        }
    }
    public function iscCompTheorySampleQuesAction()
    {
    }
    public function iscCompFindOutputTypeQuesAction()
    {
    }

	private function groupQuestionsByYear($results){

		$questions = array();

		try{

			foreach ($results as $result){

				if(array_key_exists($result['year'], $questions)){
					array_push($questions[$result['year']], $result);
				}else{
					$questions[$result['year']] = array();
					array_push($questions[$result['year']], $questions);

				}

			}
		}catch (Exception $e){
			Phototour_Logger::log($e);
		}

		return $questions;
	}


}