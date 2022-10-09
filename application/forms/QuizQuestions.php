<?php
class Form_QuizQuestions extends Zend_Form{
  public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
    $this->setAttribs(array('class' => 'form-horizontal'));

	$category=$this->createElement('select','category')
				  ->setRequired(true);
    $category->setAttrib('class','form-control');
	$categoryOptions=array(
	  'other'=>'Other',
	  'acronyms'=>'Acronyms',
	  'comp_basics'=>'Computer Basics',
	  'comp_advanced'=>'Computer Advanced',
	  'internet'=>'Internet',
	  'physics'=>'Physics',
	  'physics_advanced'=>'Physics Advanced',
	  'chemistry'=>'Chemistry',
	  'chemistry_advanced'=>'Chemistry Advanced',
	  'biology'=>'Biology',
	  'inventions'=>'Inventions',
	  'discoveries'=>'Discoveries'
	);
	foreach($categoryOptions as $key => $value){
	  $category->addMultiOption($key,$value);
	}
	$this->addElement($category);
	$question=$this->createElement('textarea','question');
	$question->setLabel('Question:');
	$question->setAttrib('class','form-control');
	$question->setRequired(true);
	$question->addFilter('StripTags');
	$question->addValidator(new Zend_Validate_StringLength(0,255));
	$this->addElement($question);
	
	$answer=$this->createElement('text','answer');
	$answer->setLabel('Answer:');
	$answer->setRequired(true);
	$answer->setAttrib('class','form-control');
	$this->addElement($answer);
	
	for($i=1;$i<=4;$i++){	
	$options=$this->createElement('text','option'.$i);
	$options->setLabel('Option '.$i.':');
	$options->setRequired(true);
	$options->setAttrib('class','form-control');
	$this->addElement($options);
	}
	
	$submit=$this->addElement('submit','submit', array('label'=>'Submit', 'class' => 'btn btn-success'));
	
  }
}