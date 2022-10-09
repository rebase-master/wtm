<?php

class QAController extends Zend_Controller_Action
{

    public function init()
    {
       $this->_helper->_layout->setLayout('_qna');
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
	    $detect = new Mobile_Detect;
	    $this->view->isMobile = $detect->isMobile();
    }

    public function indexAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity()){
            $identity=Zend_Auth::getInstance()->getIdentity();
            $this->view->uid = $identity->id;
            $this->view->username=$identity->username;
            $mdlUserData = new Model_UserData();
            $dpThumb = $mdlUserData->findPhotoById($identity->id);
            if($dpThumb==null){
                
              if($identity->gender=='m')
                    $dpThumb="/images/mt.png";
              else
                    $dpThumb="/images/ft.png";
            }else{
          $dpThumb="/users/thumbs/".$identity->username;
        }
            $this->view->dpThumb=$dpThumb;
        }else{
            $this->view->uid = -1;
        }
        $mdlQAQuestions=new Model_QAQuestions();
        $filterInt=new Zend_Filter_Int();
        if($this->getRequest()->getParam('sort')){
            $sorter = $this->getRequest()->getParam('sort');
            if($sorter === 'popular')
                $programs=$mdlQAQuestions->popularQuestions();
            else if($sorter === 'unanswered')
                $programs=$mdlQAQuestions->unansweredQuestions();
            else
                $programs=$mdlQAQuestions->recentQuestions();

            $this->view->sorter = $sorter;
        }else{
            $programs = $mdlQAQuestions->recentQuestions();
            $this->view->sorter = 'recent';
        }
//        echo "total: ".count($programs);
//        die;
        if(!empty($programs)){
            $currentPage = 1;
            $i = $this->getRequest()->getParam('page');
            $i = $filterInt->filter($i);
            if(!empty($i)){
                $currentPage = $this->getRequest()->getParam('page');
            }
            $paginator=Zend_Paginator::factory($programs);

            //set the properties for pagination
            $itemsPerPage = 15;
            $paginator->setItemCountPerPage($itemsPerPage);
            $paginator->setPageRange(ceil(count($programs)/$itemsPerPage));
            $paginator->setCurrentPageNumber($currentPage);
            $this->view->paginator = $paginator;
            $this->view->pageRange = count($programs);
            $this->view->currentPage = $currentPage;
        }else{
            $this->view->msg=1;
        }
    }

    public function questionsAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity()){
            $identity = Zend_Auth::getInstance()->getIdentity();
            $this->view->uid = $uid = $identity->id;
            $this->view->username = $identity->username;
            $mdlUserData = new Model_UserData();
            $dpThumb = $mdlUserData->findPhotoById($identity->id);
            if($dpThumb==null){

              if($identity->gender=='m')
                    $dpThumb="/images/mt.png";
              else
                    $dpThumb="/images/ft.png";
            }else{
		        $dpThumb="/users/thumbs/".$identity->username;
		    }
            $this->view->dpThumb=$dpThumb;
        }else{
            $this->view->uid = $uid = -1;
        }
        $mdlQAQuestions = new Model_QAQuestions();
        $filterInt=new Zend_Filter_Int();

        if($this->getRequest()->getParam('tagged')){
            $tag = strtolower($this->getRequest()->getParam('tagged'));
            $programs = $mdlQAQuestions->findQuesByTag($tag);
            $this->view->tag = $tag;
            if(!empty($programs)){
                $currentPage = 1;
                $i = $this->getRequest()->getParam('page');
                $i = $filterInt->filter($i);
                if(!empty($i)){
                    $currentPage = $this->getRequest()->getParam('page');
                }
                $paginator = Zend_Paginator::factory($programs);

                //set the properties for pagination
                $itemsPerPage = 10;
                $paginator->setItemCountPerPage($itemsPerPage);
                $paginator->setPageRange(ceil(count($programs)/$itemsPerPage));
                $paginator->setCurrentPageNumber($currentPage);
                $this->view->paginator = $paginator;
                $this->view->pageRange = count($programs);
                $this->view->currentPage = $currentPage;
            }else{
                $this->view->msg = 1;
            }

        }else{
            if($this->getRequest()->getParam('id')){
                $id = $filterInt->filter($this->getRequest()->getParam('id'));
                $mdlQAAnswers = new Model_QAAnswers();
                $mdlQAQuestionVotes = new Model_QAQuestionVotes();
                $mdlQAAnswerVotes = new Model_QAAnswerVotes();
                $this->view->userQuestionVotes = $r = $uid == -1? null: $mdlQAQuestionVotes->userVotes($uid);
                $this->view->userAnswerVotes = $r = $uid == -1? null: $mdlQAAnswerVotes->userVotes($uid);
                $this->view->question = $t = $mdlQAQuestions->findById($id);
                if(!empty($t)){
                    $this->view->relatedQuestions = $t = $mdlQAQuestions->relatedQuestions($t['tags'], $t['id']);
                }
                $this->view->answers = $t = $mdlQAAnswers->getAnswers($id);

            }
        }
    }

    public function askQuestionAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity()){

            $frmPrograms = new Form_AskPrograms();
            $this->view->form = $frmPrograms;

        }else{
            return $this->getResponse()->setRedirect(BASE_URL.'user/login?next='.urlencode(BASE_URL.'qa/ask-question'));
        }
    }

    public function ajaxAddQuestionAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            if(Zend_Auth::getInstance()->hasIdentity()){
                require_once APPLICATION_PATH.'/Misc/Util.php';
                $data= $this->getRequest()->getParam('data');
                $identity=Zend_Auth::getInstance()->getIdentity();
                $data['uid'] = $uid = $identity->id;

                $flag=false;
                $question = $data['question'] = Util::convert_smart_quotes(htmlentities(trim($data['question'])));
                $data['description'] = Util::convert_smart_quotes(htmlentities(trim($data['description'])));

                $mdlQAQuestions=new Model_QAQuestions();
                $result = $mdlQAQuestions->addQuestion($data);

                if($result){
                    $firstName = $identity->first_name;
                    $lastName = $identity->last_name;
                    $username = $identity->username;

                    $fromName = !empty($firstName) && !empty($lastName)? ($firstName." ".$lastName):$username;
                    $toName =  Zend_Registry::get("config")->admin->toName;
                    $toEmail =  Zend_Registry::get("config")->admin->email;

                    $questionLink = BASE_URL.'qa/questions/id/'.$result;

                    try{
                        $this->notifyAdmin($fromName, $questionLink, $toEmail, $toName, $question);
                    } catch (Exception $e) {
                        Phototour_Logger::log($e);
                    }
                    echo $result;
                }else{
                    echo -9999;
                }
            }else{
                echo -1; //Not logged in
            }
            exit;
        }
    }

	public function ajaxRemoveQuestionAction(){

		$identity=Zend_Auth::getInstance()->getIdentity();
		$data = $this->_request->getPost();
		$filter = new Zend_Filter_Int();
		$pid=$filter->filter($data['pid']);
		$mdlPosts=new Model_QAQuestions();
		$result = $mdlPosts->removeQuestion($pid,$identity->id);
		echo $result;
		exit;

	}

	public function ajaxAddCommentAction(){
		require_once APPLICATION_PATH.'/Misc/Util.php';
		if(Zend_Auth::getInstance()->hasIdentity()){
			$identity=Zend_Auth::getInstance()->getIdentity();
			$filter = new Zend_Filter_Int();
			$pid = $filter->filter($this->_request->getParam('pid'));
			$comment=Util::convert_smart_quotes(htmlentities(trim($this->_request->getParam('comment'))));
			$uid=$identity->id;
			$mdlProgramAsked = new Model_QAQuestions();

//			$mdlUserData=new Model_UserData();
//			$dpThumb=$mdlUserData->findPhotoById($uid);
//			var_dump($dpThumb);
//			echo "dp: ".$dpThumb['data'];
//			exit;
			$question = $mdlProgramAsked->findById($pid);
			if($uid != $question['uid']){
				$firstName = $identity->first_name;
				$lastName = $identity->last_name;
				$username = $identity->username;

				$fromName = !empty($firstName) && !empty($lastName)? ($firstName." ".$lastName):$username;
				$toName = !empty($question['first_name'])? $question['first_name']:$question['username'];
				$toEmail = $question['email'];
				$questionLink = BASE_URL.'qa/questions/id/'.$pid;
				try{
					$this->emailOnComment($fromName, $questionLink, $toEmail, $toName, trim(substr($comment, 0, 20)));
				} catch (Exception $e) {
					Phototour_Logger::log($e);
					echo 0;
				}
			}

			$mdlProgramAskComments=new Model_QAAnswers();
			if($pid != 0)
				$row=$mdlProgramAskComments->add($pid,$uid,$comment);

			if(!empty($row)){

				//Prepare to send email to the person who asked the question

				$mdlUserData = new Model_UserData();
				$dpThumb = $mdlUserData->findPhotoById($uid);

				if($identity->gender=='m')
					$dp = BASE_URL."images/mt.png";
				else
					$dp = BASE_URL."images/ft.png";

				if(!empty($dpThumb)){

					if(strpos($dpThumb->data, 'http') === 0 || strpos($dpThumb->data, 'https') === 0)
						$dp = $dpThumb->data;
					else {
//						TODO: Check if thumb file exists then return it
//						$thumb1 = BASE_URL."users/thumbs/".$identity->username;
//						$thumb2 = BASE_URL.$dpThumb->data;
//
//						if (file_exists($thumb1) == true){
//							$dp = $thumb1;
//						}else
//							if(file_exists($thumb2) == true) {
//								$dp = $thumb2;
//							}
						$dp = BASE_URL.$dpThumb->data;
					}
				}
				echo json_encode(array('cid' => $row->id, 'username' => $identity->username, 'time'=>'a few seconds ago', 'dp'=>$dp, 'comment'=>nl2br($comment)));
			}
			else
				echo 0;
		}else{
			echo -1;
		}
		exit;
	}

	public function ajaxRemoveCommentAction(){

		$identity=Zend_Auth::getInstance()->getIdentity();
		$data = $this->_request->getPost();
		$filter = new Zend_Filter_Int();
		$cid=$filter->filter($data['cid']);
		$mdlProgramAskComments=new Model_QAAnswers();
		$result=$mdlProgramAskComments->removeAnswer($cid,$identity->id);
		exit;
	}

	public function ajaxQuestionVoteAction(){

		$identity=Zend_Auth::getInstance()->getIdentity();
		$data = $this->getRequest()->getParams();
		$filter = new Zend_Filter_Int();
		$pid = $filter->filter($data['pid']);
		$mode = $filter->filter($data['mode']);

		$mdlQAQuestionVote = new Model_QAQuestionVotes();
		if($mode == 1)
			$result = $mdlQAQuestionVote->add($pid,$identity->id);
		else
			$result = $mdlQAQuestionVote->subtract($pid,$identity->id);

		echo 1;

		exit;
	}

	public function ajaxCommentVoteAction(){

		$identity=Zend_Auth::getInstance()->getIdentity();
		$data = $this->_request->getPost();
		$filter = new Zend_Filter_Int();
		$cid=$filter->filter($data['cid']);
		$mode=$filter->filter($data['mode']);

		$mdlQAAnswerVote = new Model_QAAnswerVotes();
		if($mode==1)
			$result = $mdlQAAnswerVote->add($cid,$identity->id);
		else
			$result = $mdlQAAnswerVote->subtract($cid,$identity->id);

		echo 1;

		exit;
	}

	public function ajaxFindTagsAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			$mdlTags = new Model_Tags();
			$tags = $mdlTags->findAll();
			if($tags)
				echo json_encode($tags);
			else
				echo -9999;
			exit;
		}else{
			throw new Zend_Acl_Exception;
		}
	}

	private function notifyAdmin($fromName, $questionLink,  $toEmail, $toName, $comment){
		if(APPLICATION_ENV != 'development') {
			$mail = new Zend_Mail();

			$mail->setFrom('admin@wethementors.com', 'Mentors');
			$mail->addTo($toEmail,
				"{$toName}");
			$mail->setSubject('Someone asked a question on wethementors.com!');
			include "_text_admin_new_ques_notif.phtml";
			$mail->setBodyText($email);
			include "_html_admin_new_ques_notif.phtml";
			$mail->setBodyHtml($htmlbody);
			$mail->send();
		}
    }

    private function emailOnComment($fromName, $questionLink,  $toEmail, $toName, $comment){

	    if(APPLICATION_ENV != 'development'){

		    $mail=new Zend_Mail();

		    $mail->setFrom('admin@wethementors.com', 'Mentors');
		    $mail->addTo($toEmail,
			    "{$toName}");
		    $mail->setSubject('Your question has a response!');
		    include "_text_questionResponse.phtml";
		    $mail->setBodyText($email);
		    include "_html_questionResponse.phtml";
		    $mail->setBodyHtml($htmlbody);
		    $mail->send();

	    }
    }

}





