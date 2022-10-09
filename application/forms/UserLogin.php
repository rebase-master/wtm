<?php
class Form_UserLogin extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	
	$email=$this->createElement('text','email');
	$email->setLabel('Email: ');
	$email->addValidator(new Zend_Validate_EmailAddress());
	$email->addValidator(new Zend_Validate_StringLength(0,100));
	$email->addErrorMessage('Please enter a valid e-mail address.');
	$email->setRequired(true);
	$email->setAttrib('class', 'form-control');
	$email->addFilter('StripTags');
	$this->addElement($email);

	$password=$this->createElement('password','password');
	$password->setLabel('Password: ');
	$password->addValidator(new Zend_Validate_StringLength(6,15));
	$password->addErrorMessage('Password cannot be blank.');
	$password->setRequired(true);
	$password->setAttrib('class', 'form-control');
	$this->addElement($password);
	
	$submit= $this->createElement('submit','submit');
    $submit->setLabel('Log In');
    $submit->setAttrib('class', 'btn btn-primary');
    $this->addElement($submit);
	
  }
}
