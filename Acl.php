<?php
class SITE_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
  
  public function preDispatch(Zend_Controller_Request_Abstract $request){
	try{
	$acl=new Zend_Acl();
	
	//add the roles
	$acl->addRole(new Zend_Acl_Role('guest'));
	$acl->addRole(new Zend_Acl_Role('user'), 'guest');
	$acl->addRole(new Zend_Acl_Role('administrator'), 'user');
	
	//add the resources
	//$acl->add(new Zend_Acl_Resource('index'));
	//$acl->add(new Zend_Acl_Resource('error'));
	//$acl->add(new Zend_Acl_Resource('applicationError'));
	$acl->add(new Zend_Acl_Resource('home'));
	$acl->add(new Zend_Acl_Resource('profile'));
	$acl->add(new Zend_Acl_Resource('forum'));
	$acl->add(new Zend_Acl_Resource('user'));
	$acl->add(new Zend_Acl_Resource('programming-in-java'));
	$acl->add(new Zend_Acl_Resource('resources'));
	$acl->add(new Zend_Acl_Resource('quotes'));
	$acl->add(new Zend_Acl_Resource('isc'));
	$acl->add(new Zend_Acl_Resource('icse'));
	$acl->add(new Zend_Acl_Resource('fun'));
	$acl->add(new Zend_Acl_Resource('programs'));
	$acl->add(new Zend_Acl_Resource('index'));
	
	//set the access rules
	//$acl->allow(null,array('index','error'));
	
	//a guest can only read content and login
	$acl->allow('guest', 'home', array('index','coaching-results','sitemap','trial-class','contact-us','icse-results','isc-results'));
	$acl->allow('guest','forum',array('index','category','topics','reply'));
	$acl->allow('guest','user', array('verify','password-recovery','reset','login',
				'reset-password','logout', 'register'));
	$acl->allow('guest','programming-in-java');
	$acl->allow('guest','resources', array('index','tutorials','people','english-language','english-literature','installing-ant'));
	$acl->allow('guest','icse',array('index','syllabus'));
	$acl->allow('guest','isc', array('index','syllabus','isc-computer-practical','viva-voce','isc-comp-find-output-type-ques','isc-comp-theory-sample-ques'));
	$acl->allow('guest','fun',array('index','brainteasers','fun-facts','java-quiz'));
	$acl->allow('guest','quotes',array('index'));
	$acl->deny('guest','programs');
	$acl->allow('guest','index', array('index'));
	$acl->allow('guest','programs',array('questions'));
	//cms users can also work with content
	
	$acl->allow('user','forum',array('create-category-heading','create-category',
				'create-topic','delete-reply','delete-topic'));
	$acl->allow('user','fun',array('java','java-beginner'));
	$acl->allow('user','icse',array('sample-papers', 'previous-years'));
	$acl->allow('user','isc', array('computer-project','sample-papers', 'previous-years','solved-isc-computer-practical','viva-voce'));
    $acl->allow('user','profile',array('index','messages', 'add-friend','edit-profile','friends','confirm-requests','ajax-filter-friends',
                'send-message','change-dp', 'afr','unfriend'));
	$acl->allow('user','programs',array('ask-question', 'questions', 'ajax-remove-question', 'ajax-remove-comment','ajax-add-comment',
		    'ajax-add-comment-vote'));
	
	//administrators can do anything
	//$acl->allow('administrator','programming-in-java');
	$acl->allow('administrator', 'index');
	$acl->allow('administrator', 'programs');
	$acl->allow('administrator',null);
  
	//fetch the current user
	$auth=Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	  $identity=$auth->getIdentity();
	  $username=strtolower($identity->username);
	  if($username=='admin1984' || $username=='kingmansoor')
		$role='administrator';
	  else
		$role='user';
	}else{
	  $role='guest';
	}
	$controller=$request->controller;
	$action=$request->action;
	
	if(!$acl->isAllowed($role, $controller, $action)){
	  if($role=='guest'){
		$request->setControllerName('Applicationerror');
		$request->setActionName('noauth');

		//$request->setControllerName('user');
		//$request->setActionName('login');
	  }else{
		$request->setControllerName('Applicationerror');
		$request->setActionName('noauth');
	  }
	}
	}catch(Exception $e){
	$controller=Zend_Controller_Front::getInstance();
	}
  }
}
