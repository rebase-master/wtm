<?php
class Form_CoachingResults extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);

	$class=$this->createElement('select','grade');
	$class->setLabel('*Class: ');
	$class->setRequired(true);
	$class->setAttrib('cols',15);
	  $values=array(
		'X'=>'X',
		'XII'=>'XII',
	  );
	  foreach($values  as $key => $value){
        $class->addMultiOption($key, $value);    
        }
	$class->setValue('X');

	$this->addElement($class);
	
	$subject=$this->createElement('select','subject');
	$subject->setLabel('*Subject: ');
	$subject->setRequired(true);
	$subject->setAttrib('cols',15);
	  $values=array(
		'english'=>'English',
		'hindi'=>'Hindi',
		'physics'=>'Physics',
		'chemistry'=>'Chemistry',
		'mathematics'=>'Maths',
		'biology'=>'Biology',
		'computer_science'=>'Computer Science',
		'computer_applications'=>'Computer Applications',
		'accounts'=>'Accounts',
		'economics'=>'Economics',
		'commerce'=>'Commerce',
		'hist_geog'=>'Hist/Geog',
	  );
	  foreach($values  as $key => $value){
        $subject->addMultiOption($key, $value);    
        }
	$subject->setValue('computer_science');
	$this->addElement($subject);
	
	$school=$this->createElement('text','school');
	$school->setLabel('*School: ');
	$school->setAttrib('size',50);
	$school->addFilter('StripTags');
	$school->addValidator(new Zend_Validate_StringLength(0,60));
	$school->addErrorMessage('Length must not exceed 60 characters.');
	$school->setRequired(true);
	$this->addElement($school);
	
	$marks=$this->createElement('text','marks');
	$marks->setLabel('*Marks: ');
	$marks->setAttrib('size',1);
	$marks->addValidator(new Zend_Validate_StringLength(0,100));
	$marks->addValidator(new Zend_Validate_Digits(0,9));
	$marks->addErrorMessage('Marks must be between 0 and 100');
	$marks->setRequired(true);
	$marks->setOrder(5);
	$this->addElement($marks);
	
	$firstName=$this->createElement('text','first_name');
	$firstName->setLabel('First Name: ');
	$firstName->addFilter('StripTags');
	$firstName->addValidator(new Zend_Validate_StringLength(2,100));
	$firstName->setRequired(true);
	$firstName->addFilter('StripTags');
	$firstName->setOrder(1);
	$this->addElement($firstName);
	
	$lastName=$this->createElement('text','last_name');
	$lastName->setLabel('Last Name: ');
	$lastName->addFilter('StripTags');
	$lastName->addValidator(new Zend_Validate_StringLength(2,100));
	$lastName->setRequired(true);
	$lastName->addFilter('StripTags');
	$lastName->setOrder(2);
	$this->addElement($lastName);

	$year=$this->createElement('select','year');
	$year->setLabel('*Year: ');
	$year->setRequired(true);
	$year->setAttrib('cols',15);
	  $values=array(
		'2004'=>'2004',
		'2005'=>'2005',
		'2006'=>'2006',
		'2007'=>'2007',
		'2008'=>'2008',
		'2009'=>'2009',
		'2010'=>'2010',
		'2011'=>'2011',
		'2012'=>'2012',
	  );
	  $values=array_reverse($values);
	  foreach($values  as $key => $value){
        $year->addMultiOption($key, $value);    
        }
	$year->setValue('0');
	$this->addElement($year);
	
	$gender=$this->createElement('select','gender');
	$gender->setLabel('Gender: ');
	$gender->addMultiOption('m','male');
	$gender->addMultiOption('f','female');
	$this->addElement($gender);

	$submit=$this->addElement('submit','submit', array('label'=>'Submit'));
	
  }
}