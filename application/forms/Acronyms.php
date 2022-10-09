<?php
class Form_Acronyms extends Zend_Form{
    private $category;
    private $data;
    public function __construct($category){
        $this->category=$category;
        //$this->init();
    }
    public function setData($data){
        $this->data=$data;
    }
    public function getResults(){
        return $this->data;
    }
    public function test(){
        $form=new Zend_Form();
        $form->setMethod('POST');
        $form->setAction('quiz-results');
    $mdlQuizQuestions=new Model_QuizQuestions();
    $mdlQuizOptions=new Model_QuizOptions();
  
    $result=$mdlQuizQuestions->getQuesByCategory($this->category);
    if(count($result)>1){  
    	$this->setData($result);
      $i=1;
      foreach($result as $q){
        $form->addElement('radio','options'.$i);
        $optionElement=$form->getElement('options'.$i);
        $optionElement->setLabel($i.'. '.$q->question);
        $options=$mdlQuizOptions->getOptionsByQues($q->id);
		$j=1;
        foreach($options as $option){
        $optionElement->addMultiOption($j++, $option->options);
		}
    $form->addElement($optionElement);
        $i++;
      }
    }
	$decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);
	
	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Submit');
	$submit->setDecorators($decorator);
	$submit->setAttrib('class', 'btn btn-success btn-lg');
	$form->addElement($submit);
	
	$cancel=$this->createElement('reset','reset');
	$cancel->setLabel('Reset');
	$cancel->setDecorators($decorator);
	$cancel->setAttrib('class', 'btn btn-danger btn-lg');
	$form->addElement($cancel);
    return $form;
    }
}