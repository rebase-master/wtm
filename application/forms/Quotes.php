<?php
class Form_Quotes extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        
        $author=$this->createElement('text', 'author');
        $author->setRequired(false);
        $author->setLabel('Author: ');
        $this->addElement($author);
        
        $quote = $this->createElement('textarea', 'quote');
        // element options
        $quote->setLabel('Quote/Proverb: ');
        $quote->setRequired(true); 
        $quote->setAttrib('cols',40); 
        $quote->setAttrib('rows',5);
        // add the element to the form
        $this->addElement($quote);
	
    $decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Submit');
	$submit->setDecorators($decorator);
	$this->addElement($submit);

    }
 }