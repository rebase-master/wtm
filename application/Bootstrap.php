<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView(){

        // Initialize view
        $view = new Zend_View();
        $view->doctype('HTML5');
        $view->headTitle('Wethementors - Providing educational assistance to inquisitive minds since 2003.');
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->setView($view);
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    protected function _initLogging()
    {
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log');
        $logger = new Zend_Log($writer);
        Zend_Registry::set('logger', $logger);
    }
    protected function _initRoutes()
    {
	    $frontController = Zend_Controller_Front::getInstance();
	    $router = $frontController->getRouter();

	    $root = new Zend_Controller_Router_Route('/',
		    array(
			    'module'     => 'default',
			    'controller' => 'home',
			    'action'     => 'index',
		    )
	    );

	    $login = new Zend_Controller_Router_Route('/login',
		    array(
			    'module'     => 'default',
			    'controller' => 'user',
			    'action'     => 'login',
		    )
	    );

	    $logout = new Zend_Controller_Router_Route('/logout',
		    array(
			    'module'     => 'default',
			    'controller' => 'user',
			    'action'     => 'logout',
		    )
	    );

	    $register = new Zend_Controller_Router_Route('/register',
		    array(
			    'module'     => 'default',
			    'controller' => 'user',
			    'action'     => 'register',
		    )
	    );

	    $profile = new Zend_Controller_Router_Route('u/:username',
            array(  'controller' => 'profile',
                    'action'     => 'index',
                    'username'   => ''
            )
        );

	    $people = new Zend_Controller_Router_Route('people/:slug',
            array(  'controller' => 'resources',
                    'action'     => 'people',
	                'slug'       => ''
            )
        );

	    $program = new Zend_Controller_Router_Route('program/:slug',
            array(  'controller' => 'programs',
                    'action'     => 'index',
	                'slug'       => ''
            )
        );

	    $category = new Zend_Controller_Router_Route('category/:slug',
            array(  'controller' => 'programs',
                    'action'     => 'category',
	                'slug'       => ''
            )
        );

	    $iscYearly = new Zend_Controller_Router_Route('isc/:subject/:type/:slug',
            array(  'controller' => 'programs',
                    'action'     => 'yearly-question',
	                'subject'       => '',
	                'type'       => '',
	                'slug'       => ''
            )
        );

	    $quote = new Zend_Controller_Router_Route('quote/:slug',
            array(  'controller' => 'quotes',
                    'action'     => 'find-by-slug',
	                'slug'       => ''
            )
        );

	    $trivia = new Zend_Controller_Router_Route('trivia',
            array(  'controller' => 'fun',
                    'action'     => 'trivia',
            )
        );

	    $riddles = new Zend_Controller_Router_Route('riddles',
            array(  'controller' => 'fun',
                    'action'     => 'riddles',
            )
        );

	    $puc = new Zend_Controller_Router_Route('page-under-construction',
            array(  'controller' => 'quiz',
                    'action'     => 'puc',
            )
        );

	    $subject = new Zend_Controller_Router_Route('subject/:param',
		    array(
			    'module'     => 'default',
			    'controller' => 'notes',
			    'action'     => 'subject',
			    'param'      => ''
		    )
	    );

	    $notes = new Zend_Controller_Router_Route('notes/:param',
		    array(
			    'module'     => 'default',
			    'controller' => 'notes',
			    'action'     => 'topic',
			    'param'      => ''
		    )
	    );

	    $subjectNotes = new Zend_Controller_Router_Route('notes/:category/:slug',
		    array(
			    'module'     => 'default',
			    'controller' => 'notes',
                'action'     => 'subject-notes',
			    'category'   => '',
			    'slug'       => ''
            )
        );

	    $router->addRoute('root', $root);
	    $router->addRoute('login', $login);
	    $router->addRoute('logout', $logout);
	    $router->addRoute('register', $register);
	    $router->addRoute('profile', $profile);
	    $router->addRoute('people', $people);
	    $router->addRoute('program', $program);
	    $router->addRoute('iscYearly', $iscYearly);
	    $router->addRoute('quote', $quote);
	    $router->addRoute('category', $category);
	    $router->addRoute('trivia', $trivia);
	    $router->addRoute('riddles', $riddles);
	    $router->addRoute('puc', $puc);
	    $router->addRoute('subject', $subject);
//	    $router->addRoute('notes', $notes);
	    $router->addRoute('subjectNotes', $subjectNotes);

    }

    protected function _initAutoLoad(){
        //Add autoloader empty namespace
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->registerNamespace('SITE_');
        $autoLoader->registerNamespace('Phototour_');
//            $autoLoader->registerNamespace('Raven_');
        $resourceLoader = new Zend_Loader_Autoloader_Resource(
            array('basePath' => APPLICATION_PATH , 'namespace' => '' ,
                'resourceTypes' => array(
                    'form' => array('path' => 'forms/' , 'namespace' => 'Form_') ,
                    'model' => array('path' => 'models/' , 'namespace' => 'Model_')
                ))
        );

        if(APPLICATION_ENV === 'development') {
	        $baseUrl = 'http://wtmlocal.com/';
	        $fbAppId     = '1487851401463358';
	        $fbAppSecret = 'efd98453ea574899ecfc418f8aa62095';
        }
        else if(APPLICATION_ENV === 'staging' || APPLICATION_ENV === 'test'){
	        $baseUrl     = 'https://test.wethementors.com:81/';
	        $fbAppId     = '1487851401463358';
	        $fbAppSecret = 'efd98453ea574899ecfc418f8aa62095';
        }
        else{
	        $baseUrl     = 'https://www.wethementors.com/';
	        $fbAppId     = '1436848579896974';
	        $fbAppSecret = '4f8927f257e67fb18e68106eb80e6928';
        }

        defined('BASE_URL') || define('BASE_URL', $baseUrl);
        defined('FACEBOOK_APP_ID') || define('FACEBOOK_APP_ID', $fbAppId);
        defined('FACEBOOK_APP_SECRET') || define('FACEBOOK_APP_SECRET', $fbAppSecret);

        $config=array(
            'ssl'	   => 'ssl',
            'port'	   => '465',
            'auth'	   => 'login',
            'username' => 'registration@wethementors.com',
            'password' => 'Plutoid!23)98',
        );
        $transport=new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
        Zend_Mail::setDefaultTransport($transport);
        //Return it so that it can be stored by the bootstrap
        return $autoLoader;
    }
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
    }


}

