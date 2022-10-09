<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->_layout->setLayout('layout1');
    }

    public function indexAction()
    {
        // action body
	$this->_redirect('./home');
    }

    public function adminLoginAction()
    {
        $frmUser=new Form_User();
        $frmUser->removeElement('first_name');
        $frmUser->removeElement('last_name');
        $frmUser->removeElement('email');
        $frmUser->removeElement('retyped_password');
        $frmUser->removeElement('gender');
        $frmUser->removeElement('captcha');
        $frmUser->getElement('submit')->setLabel('Login');
        $frmUser->getElement('username')->clearValidators();
		$frmUser->getElement('username')->setLabel('username')->setValue('');
        $frmUser->getElement('password')->setLabel('password')->setValue('');
        
        if($this->_request->isPost() && $frmUser->isValid($_POST)){
			$data=$frmUser->getValues();
			if($data['username']=='admin-of-admins' && $data['password']=='a65b66z90!'){
				$this->_redirect('./index/manage');
			}else{
				$this->_redirect('./home');
                }
            }
        $frmUser->setAction('./admin-login');
        $this->view->form=$frmUser;
        
    }
	public function manageAction(){
        $this->_helper->_layout->setLayout('admin_1col_rsp');
	}
	public function analyticsAction(){
        $this->_helper->_layout->setLayout('admin_1col_rsp');
	}
    public function addCoachingResultAction()
    {
        $form=new Form_CoachingResults();
		if($this->_request->isPost()){
            if($form->isValid($_POST)){
                $data=$form->getValues();
                $subject=$_POST['subject'];
                //echo $subject;
                //die;
                $mdlSubjects=new Model_Subjects();
                $subjectId=$mdlSubjects->findIdByName($subject);
        if($subjectId!=null){
                $id=$subjectId['id'];
                //echo $id;
                //die;
                $mdlCoachingResults=new Model_CoachingResults();
                $result=$mdlCoachingResults->add($id,$data);
                $this->view->msg=$result!=null?'Record added Successfully':'Error Adding Record';
            $form->reset();
            }else{
            throw new Zend_Exception('Subject Not Found!');
        }
        }
        }
        $this->view->form=$form;
    }
    //Add analytics for download clicks
    public function ajADwnsAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $identity=Zend_Auth::getInstance()->getIdentity();
            $id = $identity->id;
            $resource = strip_tags($this->getRequest()->getParam('a'));
            $mdlDownloads = new Model_Downloads();
            $mdlDownloads->add($id, $resource);
            echo 1;
            exit;
        }
    }

}