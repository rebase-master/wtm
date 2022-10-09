<?php
class Form_EditProfile extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	
	$class=$this->createElement('select','class');
	$class->setLabel('Class: ');
	$class->setAttrib('cols',15);
	$class->setAttrib('class','form-control');
	//$class->addValidator(new Zend_Validate_StringLength(0,20));
	//$class->addErrorMessage('Value must not exceed 20 characters');
	//$class->addFilter('StripTags');
	  $values=array(
		'V'=>'V',
		'VI'=>'VI',
		'VII'=>'VII',
		'VIII'=>'VIII',
		'IX'=>'IX',
		'X'=>'X',
		'XI'=>'XI',
		'XII'=>'XII',
		'Graduation'=>'Graduation',
		'Post Graduation'=>'Post Graduation'
	  );
	  foreach($values  as $key => $value){
        $class->addMultiOption($key, $value);    
        }

	$this->addElement($class);

	$school=$this->createElement('text','school');
	$school->setLabel('School/College: ');
	$school->setAttrib('size',65);
	$school->setAttrib('class','form-control');
	$school->addValidator(new Zend_Validate_StringLength(0,100));
	$school->addErrorMessage('Value must not exceed 100 characters');
	$school->addFilter('StripTags');
	$this->addElement($school);
	
	$stream=$this->createElement('select','stream');
	$stream->setLabel('Stream: ');
	$stream->setAttrib('cols',10);
	$stream->setAttrib('class','form-control');
//  	$stream->addValidator(new Zend_Validate_StringLength(0,15));
//	$stream->addErrorMessage('Value must not exceed 15 characters');
//	$stream->addFilter('StripTags');
	  $values=array(
		'Arts'=>'Arts',
		'Commerce'=>'Commerce',
		'Science'=>'Science'
	  );
	  foreach($values  as $key => $value){
        $stream->addMultiOption($key, $value);    
        }

	$this->addElement($stream);
	
	$city=$this->createElement('text','city');
	$city->setLabel('City: ');
	$city->setAttrib('size',15);
	$city->setAttrib('class','form-control');
	$city->addValidator(new Zend_Validate_StringLength(0,30));
	$city->addErrorMessage('Value must not exceed 30 characters');
	$city->addFilter('StripTags');

	$this->addElement($city);
	
	$country=$this->createElement('text','country');
	$country->setLabel('Country: ');
	$country->setAttrib('size',10);
	$country->setAttrib('class','form-control');
	$country->addValidator(new Zend_Validate_StringLength(0,60));
	$country->addErrorMessage('Value must not exceed 15 characters');
	$country->addFilter('StripTags');
	$this->addElement($country);
	
	
	$about=$this->createElement('textarea','about_me');
	$about->setLabel('About Me: ');
	$about->setAttrib('rows',8);
	$about->setAttrib('cols',50);
	$about->setAttrib('placeholder', 'Write something about yourself');
	$about->setAttrib('class','form-control');
	$about->addValidator(new Zend_Validate_StringLength(0,1000));
	$about->addErrorMessage('Value must not exceed 1000 characters');
	$about->addFilter('StripTags');
	$this->addElement($about);
	
	$ssc=$this->createElement('text','ssc');
	$ssc->setLabel('SSC: ');
	$ssc->addValidator(new Zend_Validate_StringLength(0,6));
	$ssc->addErrorMessage('Value must not exceed 6 characters');
	$ssc->addFilter('StripTags');
	$ssc->setAttrib('class','form-control');
	$this->addElement($ssc);
	
	$hsc=$this->createElement('text','hsc');
	$hsc->setLabel('HSC: ');
	$hsc->addValidator(new Zend_Validate_StringLength(0,6));
	$hsc->addErrorMessage('Value must not exceed 6 characters');
	$hsc->addFilter('StripTags');
	$hsc->setAttrib('class','form-control');
	$this->addElement($hsc);
	
	$graduation=$this->createElement('text','graduation');
	$graduation->setLabel('Graduation: ');
	$graduation->addValidator(new Zend_Validate_StringLength(0,6));
	$graduation->addErrorMessage('Value must not exceed 6 characters');
	$graduation->addFilter('StripTags');
	$graduation->setAttrib('class','form-control');
	$this->addElement($graduation);
	
	$postgraduation=$this->createElement('text','post_graduation');
	$postgraduation->setLabel('Post Graduation: ');
	$postgraduation->addValidator(new Zend_Validate_StringLength(0,6));
	$postgraduation->addErrorMessage('Value must not exceed 6 characters');
	$postgraduation->addFilter('StripTags');
	$postgraduation->setAttrib('class','form-control');
	$this->addElement($postgraduation);
	
	$email=$this->createElement('text','email');
	$email->setLabel('E-mail: ');
	$email->addValidator(new Zend_Validate_EmailAddress());
	$email->addErrorMessage('Please enter a valid e-mail address. E.g. bhagatsingh@india.com');
	$email->addFilter('StripTags');
	$email->setAttrib('class','form-control');
	$this->addElement($email);
	
	$phone=$this->createElement('text','phone');
	$phone->addValidator(new Zend_Validate_StringLength(0,15));
	$phone->addErrorMessage('Value must not exceed 15 characters');
	$phone->addFilter('StripTags');
	$phone->setLabel('Phone: ');
	$phone->setAttrib('class','form-control');
	$this->addElement($phone);
	
	$activities=$this->createElement('textarea','activities');
	$activities->setLabel('Activities and Interests: ');
	$activities->setAttrib('rows',8);
	$activities->setAttrib('cols',50);
	$activities->setAttrib('placeholder', 'Sport, Movies, Novels');
	$activities->addValidator(new Zend_Validate_StringLength(0,1000));
	$activities->addErrorMessage('Value must not exceed 1000 characters');
	$activities->addFilter('StripTags');
	$activities->setAttrib('class','form-control');
	$this->addElement($activities);


	$decorator = array(
    array('ViewHelper'),
//    array('HtmlTag', array('tag' => 'span')),
    //array('Label', array('escape' => false))
	);

	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Save Changes');
	$submit->setDecorators($decorator);
	$submit->setAttrib('class','btn btn-primary');
	$this->addElement($submit);
	
	$cancel=$this->createElement('submit','cancel');
	$cancel->setLabel('Cancel');
	$cancel->setAttrib('class','btn btn-info');
	$cancel->setDecorators($decorator);
	$this->addElement($cancel);
	
  }
}
