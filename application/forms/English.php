<?php
class Form_English extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        
        $author = $this->createElement('textarea', 'heading');
        // element options
        $author->setLabel('Question/Heading: ');
        $author->setRequired(true); 
        $author->setAttrib('cols',40); 
        $author->setAttrib('rows',5);
        $this->addElement($author);
       
        $quote = $this->createElement('textarea', 'description');
        // element options
        $quote->setLabel('Answer/Description: ');
        $quote->setRequired(true); 
        $quote->setAttrib('cols',50); 
        $quote->setAttrib('rows',16);
        // add the element to the form
        $this->addElement($quote);
	
    $decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Add');
	$submit->setDecorators($decorator);
	$this->addElement($submit);
	
	$cancel=$this->createElement('submit','cancel');
	$cancel->setLabel('Cancel');
	$cancel->setDecorators($decorator);
	$this->addElement($cancel);

    }
 }