<?php
class Form_JavaQuestions extends Zend_Form{

    public function init(){
	$this->setMethod('post');
	
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
    $this->setAttribs(array('class' => 'form-horizontal'));
	$category=$this->createElement('select','level')
					->setLabel('Level')
				  ->setRequired(true);
    $category->setAttrib('class','form-control');
	$categoryOptions=array(
	  '0'=>'Beginner',
	  '1'=>'Intermediate',
	  '2'=>'Advanced'
	);
	foreach($categoryOptions as $key => $value){
	  $category->addMultiOption($key,$value);
	}
	$this->addElement($category);
	$question=$this->createElement('textarea','question');
	$question->setLabel('Question:');
    $question->setAttrib('class','form-control');
	$question->setRequired(true);
	$this->addElement($question);
	
	$answer=$this->createElement('select','answer');
	$answer->setLabel('Answer:')
			->setRequired(true);
	$answer->setAttrib('class','form-control');
    $answers=array(
		1=>1,
		2=>2,
		3=>3,
		4=>4
	);
	foreach($answers as $key => $value){
		$answer->addMultiOption($key,$value);
	}
	$answer->setValue(1);
	$this->addElement($answer);
	
	
	for($i=1;$i<=4;$i++){	
	$options=$this->createElement('text','option'.$i);
	$options->setLabel('Option '.$i.':');
	$options->setAttrib('size','50');
	$options->setRequired(true);
    $options->setAttrib('class','form-control');
	$this->addElement($options);
	}
	
	$submit=$this->addElement('submit','submit', array('label'=>'Submit', 'class' => 'btn btn-success'));
    }
}