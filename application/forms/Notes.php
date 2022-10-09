<?php
class Form_Notes extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
	    $this->setAttrib('class', 'form-horizontal');

		$category = $this->createElement('select','category');
        $category->setLabel('Category: ', array('class' => ''));
        $category->setRequired(true);
	    $category->setAttrib('class', 'form-control');
        $mdlcategory = new Model_NotesCategory();
        $categories = $mdlcategory->readCategories();
        foreach($categories as $sub_category){
            $category->addMultiOption($sub_category->id, $sub_category->category);
        }
	    $category->addDecorator('Label', ['class' => '']);
	    $this->addElement($category);

	    $sub_category = $this->createElement('text','sub_category');
	    $sub_category->setLabel('Sub category');
	    $sub_category->setRequired(true);
	    $sub_category->setAttrib('class', 'form-control');
	    $sub_category->addDecorator('Label', ['class' => '']);

        $this->addElement($sub_category);
        
        $slug = $this->createElement('text', 'slug');
	    $slug->setAttrib('class', 'form-control');
	    $slug->setLabel('Slug');
	    $slug->setAttrib('placeholder', 'Slug');
	    $slug->setRequired(true);
	    $slug->addDecorator('Label', ['class' => '']);
        $this->addElement($slug);


	    $deleted = $this->createElement('select','deleted');
	    $deleted->setLabel('Deleted? ');
	    $deleted->addMultiOption(0,'No');
	    $deleted->addMultiOption(1,'Yes');
	    $deleted->setAttrib('class', 'form-control');
	    $deleted->addDecorator('Label', ['class' => '']);
	    $this->addElement($deleted);

	    $submit = $this->createElement('submit','submit');
	    $submit->setLabel('Submit');
	    $submit->setAttrib('class', 'btn btn-primary');
	    $this->addElement($submit);

    }
 }