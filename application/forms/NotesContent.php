<?php
class Form_NotesContent extends Zend_Form{

    public function init(){

        $this->setMethod('POST');
	    $this->setAttrib('class', 'form-horizontal');

	    $notes = $this->createElement('select','notes_id');
	    $notes->setLabel('Notes');
	    $notes->setAllowEmpty(false);
	    $notes->setRequired(true);
	    $notes->setAttrib('class', 'form-control');
	    $mdlNotes = new Model_Notes();
	    $notesList = $mdlNotes->listNotes();
	    foreach($notesList as $item){
		    $notes->addMultiOption($item->id, $item->sub_category);
	    }
	    $notes->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);

	    $this->addElement($notes);

	    $heading = $this->createElement('text', 'heading');
	    $heading->setAttrib('class', 'form-control');
	    $heading->setLabel('Heading');
	    $heading->setAttrib('placeholder', 'Heading');
	    $heading->setRequired(true);
	    $heading->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);
	    $this->addElement($heading);

	    $slug = $this->createElement('text', 'slug');
	    $slug->setAttrib('class', 'form-control');
	    $slug->setLabel('Slug');
	    $slug->setAttrib('placeholder', 'Slug');
	    $slug->setRequired(true);
	    $slug->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);
	    $this->addElement($slug);

	    $fileUploadElement = new Zend_Form_Element_File('cover_image');
	    $fileUploadElement->setAttrib('enctype', 'multipart/form-data')
		    ->setLabel('Cover Image:')
		    ->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3'])
		    ->setDestination('../public/images/content/uploads')
		    ->addValidator('Count',false,1)
		    ->addValidator('Extension',false,'jpg,png,jpeg')
		    ->addValidator(new Zend_Validate_File_FilesSize(array('max' =>1573376 )));

	    $this->addElement($fileUploadElement);

	    $fileChanged = $this->createElement('hidden','file_changed')
		    ->setValue(0);
	    $this->addElement($fileChanged);

	    $description = $this->createElement('textarea', 'content');
	    $description->setAttrib('class', 'form-control');
	    $description->setAttrib('id', 'content');
	    $description->setLabel('Content');
	    $description->setAttrib('placeholder', 'Content');
	    $description->setRequired(true);
	    $description->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);
	    $this->addElement($description);

	    $extract = $this->createElement('textarea', 'extract');
	    $extract->setAttrib('class', 'form-control');
	    $extract->setAttrib('id', 'extract');
	    $extract->setLabel('Extract');
	    $extract->setAttrib('placeholder', 'Extract');
	    $extract->setRequired(true);
	    $extract->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);
	    $this->addElement($extract);

	    $source = $this->createElement('textarea', 'source');
	    $source->setLabel('Source');
	    $source->setAttrib('class', 'form-control');
	    $source->setAttrib('placeholder', 'Source');
	    $source->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);
	    $this->addElement($source);

	    $deleted = $this->createElement('select','deleted');
	    $deleted->setLabel('Deleted? ');
	    $deleted->addMultiOption(0,'No');
	    $deleted->addMultiOption(1,'Yes');
	    $deleted->setAttrib('class', 'form-control');
	    $deleted->addDecorator('Label', ['class' => 'sr-only col-md-4 col-lg-3']);
	    $this->addElement($deleted);

	    $submit = $this->createElement('submit','submit');
	    $submit->setLabel('Submit');
	    $submit->setAttrib('class', 'btn btn-primary');
	    $this->addElement($submit);

    }
	
 }