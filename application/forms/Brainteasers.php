<?php
class Form_Brainteasers extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        
        $riddle=$this->createElement('textarea', 'riddle');
        $riddle->setRequired(true);
		$riddle->setAttrib('rows',5);
		$riddle->setAttrib('cols',70);
        $riddle->setLabel('Riddle: ');
        $this->addElement($riddle);
        
        $answer = $this->createElement('textarea', 'answer');
        // element options
        $answer->setLabel('Answer: ');
        $answer->setRequired(true); 
        $answer->setAttrib('cols',70); 
        $answer->setAttrib('rows',3);
        // add the element to the form
        $this->addElement($answer);
	
    $decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Add Riddle');
	$submit->setDecorators($decorator);
	$this->addElement($submit);
	
	$cancel=$this->createElement('submit','cancel');
	$cancel->setLabel('Cancel');
	$cancel->setDecorators($decorator);
	$this->addElement($cancel);

    }
 }