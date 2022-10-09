<?php

/**
 * Tmapi docs
 *
 * @category   TeliportMe Web
 * @package    TeliportMe
 * @copyright  Copyright (c) 2012-2014 TeliportMe Inc.
 * @version    $Id:$
 * @link       http://teliportme.com
 * @since      File available since Release 1.5.0
 */

/**
 * Action Extension
 *
 * @category   TeliportMe Web
 * @package    Phototour
 * @subpackage Phototour_Action
 */

class Phototour_Action extends Zend_Controller_Action
{

    /**
     * Object check required variables
     * @var Phototour_Sanitizer
     */
    public $_filter;

    /**
     * Object to check params
     * @var boolean
     */
    public $_is_authorized;

    /**
     * Call Redirect Function before dispatching
     *
     * @access public
     * @return void
     */

    public function init()
    {

        if ("index" == $this->getRequest()->getActionName())
            $this->redirect_action();

    }

    /**
     * Setup json context for every action
     *
     * @access public
     * @return void
     */

    public function preDispatch()
    {
        /* Initialize action controller here */
        $action = $this->getRequest()->getActionName();
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext($action, 'json')->initContext('json');

        $this->_filter = new Phototour_Sanitizer();
        $this->_filter->_params = $this->_request->getParams();

        $this->_is_authorized = Zend_Registry::get('is_authorized');

        $meta = new stdClass();
        $this->view->meta = $meta;
        $this->view->meta->code = 200;
        $this->view->server = APPLICATION_ENV;
        $this->view->response = new stdClass();

    }

    /**
     * Index Action fallback for redirect
     *
     * @access public
     * @return void
     */

    public function indexAction()
    {
        $this->redirect_action();

    }

    /**
     * Redirect Fallback
     *
     * @access public
     * @return void
     */

    public function redirect_action()
    {
        Phototour_Logger::log("redirect Called");
    }

}