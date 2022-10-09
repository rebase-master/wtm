<?php

class AjaxController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	//Function to return ques/sol by year, ques, type, subject no on XMLHttpRequest
	//Params: Year, Question num, type, subject
	//Return: Question and solution for the given year

	public function agpqbyyearAction(){

		if($this->getRequest()->isXmlHttpRequest()){

			$filter = new Zend_Filter_Int();
			$mdlYearlyQuestions         =   new Model_YearlyQuestions();
			$mdlYearlyQuestionComments  =   new Model_YearlyQuestionComments();
			$mdlYearlyQuestionTopics    =   new Model_YearlyQuestionTopics();

			$year       =   $filter->filter($this->getRequest()->getParam('year'));
			$questionNo =   $filter->filter($this->getRequest()->getParam('qno'));
			$type       =   strip_tags($this->getRequest()->getParam('type'));
			$subject    =   strip_tags($this->getRequest()->getParam('subject'));

			$result     =   $mdlYearlyQuestions->getYearlyQuestion($type, $year, $subject, $questionNo);

			if(!empty($result)){
				$comments   =   $mdlYearlyQuestionComments->getComments($result['id']);
				$tags       =   $mdlYearlyQuestionTopics->getTopicsByQuestionId($result['id']);
				$related    =   count($tags) > 0 ? $mdlYearlyQuestions->getByTags($tags, 5) : null;

				$result['question']     =   htmlspecialchars_decode($result['question']);
				echo $this->_helper->json(
					array(
						'data'      =>  $result->toArray(),
						'comments'  =>  $comments->toArray(),
						'tags'      =>  $tags->toArray(),
						'rques'     =>  !empty($related)?$related->toArray():null,
						'status'    =>  1
					)
				);
			}else{
				echo $this->_helper->json(array('data' => null, 'status' => -1));
			}

		}else{
			throw new Zend_Acl_Exception;
		}

	}

	public function addCommentYqAction()
	{
		if(Zend_Auth::getInstance()->hasIdentity()){
			$identity = Zend_Auth::getInstance()->getIdentity();

			if($this->getRequest()->isXmlHttpRequest()){
				$data         = $this->getRequest()->getParams();
				$data['uid']  = $identity->id;

				$mdlYearlyQuestionComments = new Model_YearlyQuestionComments();

				if($mdlYearlyQuestionComments->createComment($data))
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


}//end of class



