<?php
class Form_Contact extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	
	$name=$this->createElement('text','name');
	$name->setLabel('Full Name: ');
	$name->setRequired(true);
	$name->addFilter('StripTags');
	$name->addValidator(new Zend_Validate_StringLength(2,60));
	$name->addErrorMessage('Name is required.');
	$name->addErrorMessage('Name must not exceed 60 characters.');
	$name->setAttrib('class','form-control');
	$this->addElement($name);
	
	
	$email=$this->createElement('text','email');
	$email->setLabel('E-mail: ');
	$email->addFilter('StripTags');
	$email->addValidator(new Zend_Validate_StringLength(7,45));
    $email->addErrorMessage('Email is required.');
	$email->setRequired(true);
	$email->addFilter('StripTags');
	$email->setAttrib('class','form-control');
	$this->addElement($email);
      
	$desc=$this->createElement('textarea','message');
	$desc->setLabel('Message: ');
	$desc->addFilter('StripTags');
	$desc->addValidator(new Zend_Validate_StringLength(10, 1000));
    $desc->addErrorMessage('Message cannot be empty.');
	$desc->setRequired(true);
	$desc->addFilter('StripTags');
	$desc->setAttrib('rows',5);
//	$desc->setAttrib('col',5);
	$desc->setAttrib('class','form-control');
	$this->addElement($desc);

	
		//Recaptcha API keys
	$publicKey='6Ld2N9ASAAAAABXDSdVdTUsKXMSMYDcYonFT_wUB';
	$privateKey='6Ld2N9ASAAAAAO2dYoTqwVZV_ZFv3ikLeOonCENn';
	$recaptcha=new Zend_Service_ReCaptcha($publicKey,$privateKey);
	
	//create the captcha control
	$captcha=new Zend_Form_Element_Captcha('captcha',
	  array('captcha' => 'ReCaptcha',
			'captchaOptions'=>array('captcha'=>'ReCaptcha','service'=>$recaptcha)));
	$this->addElement($captcha);


      $submit=$this->createElement('submit', 'Submit');
      $submit->setAttrib('class','btn btn-success btn-lg');
      $this->addElement($submit);
	
  }
}