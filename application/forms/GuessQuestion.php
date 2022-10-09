<?php
class Form_GuessQuestion extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        $this->setAttrib('class','form-horizontal');

        $topic=$this->createElement('select','year');
        $topic->setLabel('Year');
        $topic->setRequired(true);
        $topic->setAttrib('class', 'form-control');
		 for($i=2000; $i<=date('Y'); $i++){
            $topic->addMultiOption($i, $i);
        }
        
        $this->addElement($topic);
        
        $type=$this->createElement('select','type');
        $type->setLabel('Type');
        $type->setRequired(true);
        $type->setAttrib('class', 'form-control');
        $type->addMultiOption("guess", "guess");
        $type->addMultiOption("practical", "practical");
        
        $this->addElement($type);
        
        $question = $this->createElement('textarea', 'question');
        // element options
        $question->setLabel('Question');
        $question->setRequired(TRUE); 
        $question->setAttrib('rows',10);
        $question->setAttrib('class', 'form-control');
        // add the element to the form
        $this->addElement($question);
        
        $solution = $this->createElement('textarea', 'solution');
        // element options
        $solution->setLabel('Solution');
        $solution->setRequired(false); 
        $solution->setAttrib('rows',20);
        $solution->setAttrib('class', 'form-control');
        // add the element to the form
        $this->addElement($solution);
       
        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit', 'class' => 'btn btn-success'));
    }
}