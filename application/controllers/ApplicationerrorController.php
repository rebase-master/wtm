<?php

class ApplicationerrorController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('_error');
    }

    public function indexAction()
    {
                //Get the controller's errors.
        $errors = $this->_getParam('error_handler');
	    if(!empty($errors)) {
		    $exception = $errors->exception;

		    //Initialize view variables.
		    $this->view->exception = $exception;

		    if (APPLICATION_ENV == 'development') {
			    echo "<br /><h2>error msg from applicationerrorcontroller</h2>";
			    var_dump($errors);
		    }

		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($exception->getMessage() . "\n" .
			    $exception->getTraceAsString());
	    }
    }

    public function noauthAction()
    {
        // action body
    }


}



