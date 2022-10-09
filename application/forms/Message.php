<?php
class Form_Message extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	
	$to=$this->createElement('text','to');
	$to->setLabel('*To: ');
	$to->setAttrib('disabled','disabled');
	$to->setAttrib('readonly','readonly');
	$to->setAttrib('class','form-control');
	//$to->addFilter('StripTags');
	//$to->addValidator(new Zend_Validate_EmailAddress());
	//$to->addValidator(new Zend_Validate_StringLength(0,60));
	//$to->addErrorMessage('Email is required');
	//$to->addErrorMessage('Email must not exceed 60 characters');
	$this->addElement($to);
	
	$body=$this->createElement('textarea','body');
	$body->setAllowEmpty(false);
	$body->setLabel('Message: ');
//	$body->setAttrib('rows','12');
//	$body->setAttrib('cols','40');
	$body->setAttrib('class','form-control');
	$body->addFilter('StripTags');
	$body->addValidator(new Zend_Validate_StringLength(1,1000));
	//$body->addValidator(new Zend_Validate_NotEmpty());
	$this->addElement($body);
	
	//$decorator = array(
	//			array('ViewHelper'),
	//			array('HtmlTag', array('tag' => 'span')),
	//			//array('Label', array('escape' => false))
	//			);
	//
	$submit=$this->createElement('submit','confirm');
	$submit->setLabel('Send');
	$submit->setAttrib('class','btn btn-success');
	$this->addElement($submit);
	//
	//$cancel=$this->createElement('submit','cancel');
	//$cancel->setLabel('Cancel');
	//$cancel->setDecorators($decorator);
	//$this->addElement($cancel);
	
	    $this->setAttrib('id','div_form');
//		$this->addElement('submit', 'submit', array(
//        'label' => 'Send',
//        //'onclick' => "$('#div_form').load('" . "/wethementors/public/profile/submit" . "', $('#div_form').serializeArray() ); return false;"
//         ));
  }
}