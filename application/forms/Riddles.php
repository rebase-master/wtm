<?php
class Form_Riddles extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        $this->setAttrib('class', 'form-horizontal');
//        $decorator = array(
//            array('ViewHelper'),
//            array('HtmlTag', array('tag' => 'div', 'class' => 'form-group')),
//            //array('Label', array('escape' => false))
//        );

        $riddle=$this->createElement('textarea', 'riddle');
        $riddle->setRequired(true);
        $riddle->setAttrib('class', 'form-control');
        $riddle->setLabel('Riddle: ');
        $this->addElement($riddle);
        
        $answer = $this->createElement('textarea', 'answer');
        // element options
        $answer->setLabel('Answer: ');
        $answer->setRequired(true);
        $answer->setAttrib('class', 'form-control');
        $this->addElement($answer);
	
    $decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'p')),
				);

	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Submit');
    $submit->setAttrib('class','btn btn-success');
	$this->addElement($submit);
	
    }
 }