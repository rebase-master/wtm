<?php
class Form_Videos extends Zend_Form{
  public function init(){
	$this->setMethod('post');
    $this->setAttrib('class', 'form-horizontal');

    $id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	

	$heading=$this->createElement('text','heading');
	$heading->setLabel('Video Heading: ');
	$heading->setAttrib('class', 'form-control');
	$heading->addValidator(new Zend_Validate_StringLength(10,100));
    $heading->setRequired(TRUE);
    $heading->addErrorMessage('Heading is required.');
    $heading->addErrorMessage('Value must lie between 10 and 100 characters');
	$heading->addFilter('StripTags');
	$this->addElement($heading);
	
	$link=$this->createElement('text','link');
	$link->setLabel('Video link: ');
	$link->setAttrib('class', 'form-control');
	$link->setRequired(TRUE);
	$link->addValidator(new Zend_Validate_StringLength(10,100));
	$link->addErrorMessage('Value must lie between 10 and 100 characters');
	$link->addErrorMessage('Video Link is required.');
	$link->addFilter('StripTags');
	$this->addElement($link);
	
    $source=$this->createElement('text','source');
	$source->setLabel('Source: ');
	$source->setAttrib('class', 'form-control');
	$source->setRequired(TRUE);
	$source->addValidator(new Zend_Validate_StringLength(5,255));
	$source->addErrorMessage('Value must lie between 10 and 255 characters');
	$source->addErrorMessage('Source is required');
	$source->addFilter('StripTags');
	$this->addElement($source);
	
	$description=$this->createElement('textarea','description');
	$description->setLabel('Video description: ');
	$description->setAttrib('class', 'form-control');
	$description->addValidator(new Zend_Validate_StringLength(0,1000));
	$description->addErrorMessage('Value must not exceed 1000 characters');
	$this->addElement($description);


	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Submit');
    $submit->setAttrib('class', 'btn btn-primary');
	$this->addElement($submit);

  }
}
