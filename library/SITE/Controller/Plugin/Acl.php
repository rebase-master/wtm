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
	$acl->add(new Zend_Acl_Resource('admin'));
	$acl->add(new Zend_Acl_Resource('home'));
	$acl->add(new Zend_Acl_Resource('profile'));
	$acl->add(new Zend_Acl_Resource('quiz'));
	$acl->add(new Zend_Acl_Resource('user'));
	$acl->add(new Zend_Acl_Resource('resources'));
	$acl->add(new Zend_Acl_Resource('quotes'));
	$acl->add(new Zend_Acl_Resource('isc'));
	$acl->add(new Zend_Acl_Resource('icse'));
	$acl->add(new Zend_Acl_Resource('fun'));
	$acl->add(new Zend_Acl_Resource('programs'));
	$acl->add(new Zend_Acl_Resource('index'));
	$acl->add(new Zend_Acl_Resource('videos'));
	$acl->add(new Zend_Acl_Resource('tutorials'));
	$acl->add(new Zend_Acl_Resource('qa'));
	$acl->add(new Zend_Acl_Resource('ajax'));
	$acl->add(new Zend_Acl_Resource('test'));
	$acl->add(new Zend_Acl_Resource('programming-in-java'));
	$acl->add(new Zend_Acl_Resource('doodle'));
	$acl->add(new Zend_Acl_Resource('gallery'));
	$acl->add(new Zend_Acl_Resource('forum'));
	$acl->add(new Zend_Acl_Resource('notes'));

	//set the access rules
	//$acl->allow(null,array('index','error'));

		//a guest can only read content and login
	$acl->allow('guest', 'home', array('index','privacy-policy', 'terms-of-use','sitemap','trial-class'));
	$acl->allow('guest','user', array('verify','password-recovery','reset','login', 'social-login',
				'reset-password','logout', 'register', 'fb-user-exists', 'register-facebook', 'user-exists'));
	$acl->allow('guest','resources', array('index','tutorials','people','english-language','english-literature','installing-ant'));
	$acl->allow('guest','icse',array());
	$acl->allow('guest','isc', array());
	$acl->deny('guest', 'isc', array('computer-project'));
	$acl->allow('guest','fun',array('index','riddles','trivia','java-quiz','fun-facts','brainteasers'));
	$acl->allow('guest','quotes');
	$acl->deny('guest', 'qa');
	$acl->allow('guest','qa',array('index','questions','ajax-find-tags'));
    $acl->allow('guest','profile',array('index'));
    $acl->allow('guest','videos',array('index'));
    $acl->allow('guest','tutorials');
    $acl->allow('guest','programs');
    $acl->allow('guest','ajax');
    $acl->allow('guest','quiz', array('puc'));
    $acl->allow('guest','programming-in-java');
    $acl->allow('guest','doodle');
    $acl->allow('guest','gallery');
    $acl->allow('guest','forum');
    $acl->allow('guest','notes');

	$acl->deny('user', 'admin');
	$acl->allow('user','fun',array('java','java-beginner'));
	$acl->allow('user','profile',array('index', 'edit','edit-photo', 'change-dp'));
	$acl->allow('user','qa',array('ask-question', 'ajax-add-question', 'ajax-remove-question',
		'ajax-add-comment', 'ajax-remove-comment', 'ajax-question-vote', 'ajax-comment-vote'));

	//administrators can do anything
	//$acl->allow('administrator','programming-in-java');
	$acl->allow('administrator', 'qa');
	$acl->allow('administrator', 'admin');
	$acl->allow('administrator', 'index');
	$acl->allow('administrator', 'programs');
	$acl->allow('administrator', 'test');
	$acl->allow('administrator',null);

	//fetch the current user
	$auth=Zend_Auth::getInstance();
	if($auth->hasIdentity()){

	  $identity = $auth->getIdentity();
	  $admin = $identity->admin;

	  if($admin)
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

	  }else{
		$request->setControllerName('Applicationerror');
		$request->setActionName('noauth');
	  }
	}

	}catch(Exception $e){
		$controller=Zend_Controller_Front::getInstance();
		if(APPLICATION_ENV == 'development'){
			var_dump("Error: ", $e);
			die;
		}else{
			$request->setControllerName('Applicationerror');
			$request->setActionName('index');
		}
	}
  }
}
