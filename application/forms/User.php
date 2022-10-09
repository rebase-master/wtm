<?php
class Form_User extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	
	$username=$this->createElement('text','username');
	$username->setLabel('Username: ');
	$username->setRequired(true);
	$username->addValidator(new Zend_Validate_Alnum());
	$username->addValidator(new Zend_Validate_StringLength(0,30));
	$username->addErrorMessage('username is required');
	$username->addErrorMessage('username can only contain letters and digits.');
	$username->addErrorMessage('username must not exceed 30 characters');
    $username->setAttrib('class', 'form-control');
    $username->addFilter('StripTags');
	$this->addElement($username);
	
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
	
	$retyped_password=$this->createElement('password','retyped_password');
	$retyped_password->setLabel('Confirm Password: ');
	$retyped_password->addValidator(new Zend_Validate_StringLength(6,15));
	$retyped_password->setRequired(true);
	$retyped_password->setAttrib('class', 'form-control');
	$this->addElement($retyped_password);
	
	$firstname=$this->createElement('text','first_name');
	$firstname->setLabel('First Name: ');
	$firstname->setRequired(true);
	$firstname->setAttrib('class', 'form-control');
	//$firstname->addValidator('StringLength', false, array(0,255,'messages'=>'Cannot be more than 255 chars'));
	//$firstname->addValidator('NotEmpty', true, array('messages'=>'Cannot be empty'));

	$firstname->addValidator(new Zend_Validate_StringLength(2,100));
	$firstname->addValidator(new Zend_Validate_Alpha());
	$firstname->addErrorMessage('First name must not be empty and it can only contain letters.');
	$firstname->addFilter('StripTags');
	$firstname->setAttrib('class', 'form-control');
	$this->addElement($firstname);
	
	$lastname=$this->createElement('text','last_name');
	$lastname->setLabel('Last Name: ');
	$lastname->addValidator(new Zend_Validate_StringLength(2,100));
	$lastname->addValidator(new Zend_Validate_Alnum());
	$lastname->addErrorMessage('Last name can only contain letters.');
	$lastname->setAttrib('class', 'form-control');
	$lastname->setRequired(true);
	$lastname->addFilter('StripTags');
	$this->addElement($lastname);
	
	$gender=$this->createElement('select','gender');
	$gender->setLabel('Gender: ');
	$gender->addMultiOption('m','Male');
	$gender->addMultiOption('f','Female');
	$gender->setAttrib('class', 'form-control');
	$this->addElement($gender);
	
		//Recaptcha API keys
//	$publicKey='6Ld2N9ASAAAAABXDSdVdTUsKXMSMYDcYonFT_wUB';
//	$privateKey='6Ld2N9ASAAAAAO2dYoTqwVZV_ZFv3ikLeOonCENn';
//	$publicKey='6LfWo08UAAAAAOneFk0RxxLO6t2MmHQlxa1LoCNl';
//	$privateKey='6LfWo08UAAAAAF4HYQoohRTCXkFH0VziYx_VkC3I';
//	$recaptcha=new Zend_Service_ReCaptcha($publicKey,$privateKey);
	
	//create the captcha control
//	$captcha=new Zend_Form_Element_Captcha('captcha',
//	  array('captcha' => 'ReCaptcha',
//			'captchaOptions'=>array('captcha'=>'ReCaptcha','service'=>$recaptcha, 'ssl' => true)));
//	$this->addElement($captcha);

	
	$submit= $this->createElement('submit','submit');
    $submit->setLabel('Create Account');
    $submit->setAttrib('class', 'btn btn-primary btn-block btn-lg');
    $this->addElement($submit);
	
  }
  public function makeTime($current, $created){
	  $time=floor(($current-$created)/60);
	  $t=$time;    
	  if($time<1)
		  $t="Less than 1 minute ago";
	  else if($time>=1 && $time<2)
		  $t=$time." minute ago";
	  else if($time>=2 && $time<60)
		  $t=$time." minutes ago";
	  else if($time>=60 && $time<120)
	  $t=(floor($time/60))." hour ago";
	  else if($time>120 && $time<=1440)
	  $t=(floor($time/60))." hours ago";
	  else if($time>1440 && $time<2880)
	  $t=(floor($time/1440))." day ago";
	  else if($time>=2880 && $time<4320)
	  $t=(floor($time/1440))." days ago";
	  else if($time>=4320)
	  $t=strftime("%b %d, %Y", $created);
  return $t;
  }
  public function makeShortTime($current, $created){
	  $time=floor(($current-$created)/60);
	  $t=$time;
	  if($time<1)
		  $t="a few seconds";
	  else if($time>=1 && $time<2)
		  $t=$time." min";
	  else if($time>=2 && $time<60)
		  $t=$time." mins";
	  else if($time>=60 && $time<120)
	  $t=(floor($time/60))." hr";
	  else if($time>120 && $time<=1440)
	  $t=(floor($time/60))." hr";
	  else if($time>1440 && $time<2880)
	  $t=(floor($time/1440))." d";
	  else if($time>=2880 && $time<4320)
	  $t=(floor($time/1440))." d";
	  else if($time>=4320)
	  $t=strftime("%b %d", $created);
      //try %j for day number w/0 leading zero in Linux. It doesn't work on windows
  return $t;
  }
}
