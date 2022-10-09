<?php

class QuizController extends Zend_Controller_Action
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
        $this->_helper->_layout->setLayout('_2col');
        if(!Zend_Auth::getInstance()->hasIdentity()){
        $this->view->visitor=0;    
        }else{
            $this->view->visitor=1;    
        }
    }

	public function pucAction(){
		$this->_helper->_layout->setLayout('_error');

	}

	public function javaAction(){
		if(Zend_Auth::getInstance()->hasIdentity()){
			$identity=Zend_Auth::getInstance()->getIdentity();

		$this->_helper->layout()->setLayout('_nocol');
		if($this->getRequest()->getParam('quiz')){
			$filer=new Zend_Filter_Alpha();
			$name=$filer->filter($this->getRequest()->getParam('quiz'));

			if(strcasecmp($name,"beginner")==0 || strcasecmp($name,"intermediate")==0 || strcasecmp($name,"advanced")==0){
				if($this->getRequest()->getParam('op')){
					$op=$filer->filter($this->getRequest()->getParam('op'));
					if(strcasecmp($op,"result")==0){
					$passResult=new Zend_Session_Namespace('quiz-result');
					$this->view->solutions=$passResult->solutions;
					$this->view->answers=$passResult->answers;
					
					$mdlQuizScores=new Model_JavaQuizScores();
					$this->view->highscores=$mdlQuizScores->highscores($name);
					$this->view->quizName=$name;
					}
				}else{
				$mdlQuizQuestions=new Model_JavaQuizQuestions();
				$mdlQuizOptions=new Model_JavaQuizOptions();
					if(strcasecmp($name,'beginner')==0){
						$questions=$mdlQuizQuestions->getQuestionSetByLevel('0',10);
					}elseif(strcasecmp($name,'intermediate')==0){
						$questions=$mdlQuizQuestions->getQuestionSetByLevel('1',15);
					}elseif(strcasecmp($name,'advanced')==0){
						$questions=$mdlQuizQuestions->getQuestionSetByLevel('2',15);
					}
				
				
				if($questions!=null)
				{
					$questionSet=array();
				$i=0;
				foreach($questions as $question){
					$questionSet[$i]['id']=$question['id'];
					$questionSet[$i]['question']=nl2br($question['question']);
					$questionSet[$i]['answer']=$question['answer'];
					$options=$mdlQuizOptions->getOptionsByQues($question['id']);
					$optionSet=array();
					foreach($options as $option){
						$optionSet[]=$option['options'];
					}
					$questionSet[$i]['options']=$optionSet;
					$i++;
				}
				$beginnerSession=new Zend_Session_Namespace('ResultSession');
				$beginnerSession->questions=$questionSet;
				$beginnerSession->uid=$identity->id;
				$beginnerSession->title=$name;
				$this->_forward('java-beginner');
			}else{
					echo "Error";
				}
				}
			}
		}
	}
	}
	public function javaBeginnerAction(){
		$this->_helper->layout()->setLayout('_responsive_1col');
		$beginnerSession=new Zend_Session_Namespace('ResultSession');
		$questionSet=$beginnerSession->questions;
		$uid=$beginnerSession->uid;
		$quizLevel=$beginnerSession->title;
		
		if($this->getRequest()->getPost()){
			$data=$this->getRequest()->getPost();
			
			if(isset($data['answers'])){
				$passResult=new Zend_Session_Namespace('quiz-result');
				
				if(is_array($data['answers'])){
				$answers=$data['answers'];
				$ans=array();
				$solutions=array();
				$j=0;
				//print_r($answers);
				for($i=0;$i<count($answers);$i++){
                    if(!empty($answers[$i]) && $answers[$i] > 0){
                        $solutions[$j]['question']=$questionSet[$i]['question'];
                        $solutions[$j]['solution']=$questionSet[$i]['options'][$answers[$i]-1];
                        if($answers[$i]==$questionSet[$i]['answer']){
                            $solutions[$j++]['result']='correct';
                            $ans[]=$answers[$i];
                        }else{
                            $solutions[$j++]['result']='wrong';
                        }
					}
				}
				$mdlJavaQuizScores=new Model_JavaQuizScores();
				if($uid!=1) $mdlJavaQuizScores->save($uid,$quizLevel,count($ans)*2);
				$passResult->solutions=$solutions;
				$passResult->answers=$ans;
				}else{
				$passResult->solutions=$passResult->answers=null;
				}
				echo $quizLevel;
			exit;
			}
			$q=$data['ques']-1;
			$values=array();
			$values['id']=$questionSet[$q]['id'];
			$values['question']=$questionSet[$q]['question'];
			$i=0;
			foreach($questionSet[$q]['options'] as $options){
				$values['options'][$i++]=($options);
			}
			echo $this->_helper->json($values);
			exit;
		}else{
			$q=1;
		$this->view->title=$quizLevel;
		$this->view->questionSet=$questionSet[$q-1];
		//$this->_helper->viewRenderer->setNoRender();
		}
	}
    public function quizAction()
    {
        // action body
    }


    public function gamesAction()
    {
        // action body
    }

    public function acronymsAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity()){
            $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('Acronyms');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='Acronyms';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='acronyms';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function quizResultsAction()
    {
        $session=new Zend_Session_Namespace('session');
        $results=$session->data;
        $uid=$session->id;
        $category=$session->category;
        $username=$session->username;
        $form=new Form_Acronyms($category);
        $form1=$form->test();
        if($this->_request->isPost() && $form1->isValid($_POST)){
            $answers=array();
            foreach($results as $result)
            foreach($result as $var => $value){
                if(strcasecmp($var,'answer')==0)
                $answers[]=$value;
            }
            $data=$form1->getValues();
            $attempts=array();
            foreach($data as $var => $value){
                if(strcasecmp($var,'Submit')!=0)
                $attempts[]=isset($value)?$value:0;
            }
            $array_diff=array_intersect_assoc($answers,$attempts);
    $total_questions=count($answers);
    $correct_answers=count($array_diff);
   
    $mdlQuizCategory=new Model_QuizCategory();
    $qid=$mdlQuizCategory->findId($category);
    $mdlQuizScores=new Model_QuizScores();
    if($uid!=1)$mdlQuizScores->save($qid,$uid,$correct_answers*10);
   
    if($correct_answers<=$total_questions/2){
        $message="Never mind! You can always re-take the quiz with better results.";
    }elseif($correct_answers>=$total_questions/2 && $correct_answers<=$total_questions-2){
        $message="Great Attempt! You can always better your score by taking the test again.";
    }else{
        $message="Wonderful! You seems to be a knowledgeable person.<br />
        Try out the quiz again with some new questions to see if you can
        maintain your score.";
    }
    if(count($array_diff)==0)
    $answer="Oh <b>".$username."!</b> Unfortunately you couldn't score this time.";
    elseif(count($array_diff)==1)
    $answer="Oh <b>".$username."!</b> You got only <b>1</b> answer correct.";
    else
    $answer="<b>".$username."!</b> You got <b>".count($array_diff)."</b> answers correct.";
    
    $this->view->result=$answer;
    $this->view->message=$message;
    $this->view->score="Your score is ".($correct_answers*10)." Points";
    $this->view->highscores=$mdlQuizScores->highscores($qid);
    $this->view->page=$session->actionName;

    }else{
        $this->_redirect('./fun');
    }

    }

    public function physicsAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('Physics');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='Physics';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='physics';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function physicsAdvancedAction()
    {
            if(Zend_Auth::getInstance()->hasIdentity()){
            $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('Physics_advanced');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='Physics_advanced';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='physics-advanced';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function chemistryAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('Chemistry');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='Chemistry';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='chemistry';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function chemistryAdvancedAction()
    {
      if(Zend_Auth::getInstance()->hasIdentity()){
        $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('chemistry_advanced');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='chemistry_advanced';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='chemistry-advanced';
       
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function biologyAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('biology');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='biology';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='biology';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function computerBasicsAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('comp_basics');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='comp_basics';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='computer-basics';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function internetAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('internet');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='internet';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='internet';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function inventionsAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('inventions');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='inventions';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='inventions';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function discoveriesAction()
    {
    if(Zend_Auth::getInstance()->hasIdentity()){
    $identity=Zend_Auth::getInstance()->getIdentity();
        $form=new Form_Acronyms('discoveries');
        $form1=$form->test();
        $results=$form->getResults();
        $mdlUser=new Model_User();
        $session=new Zend_Session_Namespace('session');
        $session->category='discoveries';
        $session->data=$results;
        $session->username=$identity->username;
        $session->id=$mdlUser->getIdByUsername($identity->username);
        $session->actionName='discoveries';
        
        $this->view->form=$form1;
    }else{
        $this->_redirect('./user/login');
    }
    }

    public function triviaAction()
    {
        $this->_helper->_layout->setLayout('_responsive_2col');

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
}//end of class