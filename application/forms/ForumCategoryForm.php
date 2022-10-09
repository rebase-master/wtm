  <?php
class Form_ForumCategoryForm extends Zend_Form{
    public function init(){
    	$this->setMethod('POST');
		$decorator = array(
					array('ViewHelper'),
					array('HtmlTag', array('tag' => 'span', 'class'=>'create_heading')),
					//array('Label', array('escape' => false))
		);
		
		$heading=$this->createElement('text','category_heading');
		$heading->setLabel('Category Heading:');
		$heading->setAttrib('size',50);
		$heading->setRequired(true);
        $heading->setAttrib('class', 'form-control');
		$heading->setDecorators($decorator);
        $heading->addValidator(new Zend_Validate_StringLength(3,100));
        $heading->addErrorMessage('Category name cannot be empty and must not exceed 100 characters.');
		$this->addElement($heading);
		
		$submit=$this->createElement('submit','submit');
		$submit->setLabel('Create');
        $submit->setAttrib('class', 'btn btn-success');
		$submit->setDecorators($decorator);
		$this->addElement($submit);

	}
    public function createCategory(){
		$form=new Zend_Form();
		
    	$form->setMethod('POST');
		$decorator = array(
					array('ViewHelper'),
					array('HtmlTag', array('tag' => 'span', 'class'=>'create_heading')),
					//array('Label', array('escape' => false))
		);
		
		$heading=$form->createElement('text','category_name');
		$heading->setLabel('Category Name:');
		$heading->setAttrib('size',50);
		$heading->setRequired(true);
        $heading->setAttrib('class','form-control');
		$heading->setDecorators($decorator);
		$form->addElement($heading);
		
		$submit=$form->createElement('submit','submit');
		$submit->setLabel('Create');
		$submit->setDecorators($decorator);
        $submit->setAttrib('class','btn btn-success');
		$form->addElement($submit);

		return $form;
	}
	public function createTopic(){
		$form =new Zend_Form();
		$form->setMethod('POST');
		
		$heading=$form->createElement('text','topic_heading');
		$heading->setLabel('Topic: ');
		$heading->setRequired(true);
		$heading->setAttrib('size',74);
        $heading->addValidator(new Zend_Validate_StringLength(1,200));
        $heading->addErrorMessage('Heading cannot be empty and must not exceed 200 characters.');
		$form->addElement($heading);
		
		$description=$form->createElement('textarea', 'description');
		$description->setLabel('Description: ');
		$description->setRequired(false);
		$description->setAttrib('rows',15);
		$description->setAttrib('cols',75);
        $description->addValidator(new Zend_Validate_StringLength(1,1000));
        $description->addErrorMessage('Description cannot be empty and must not exceed 1000 characters.');
		$form->addElement($description);
		
		$decorator = array(
						array('ViewHelper'),
						array('HtmlTag', array('tag' => 'span')),
						//array('Label', array('escape' => false))
					);
	
		$submit=$form->createElement('submit','submit');
		$submit->setLabel('Create');
		$submit->setDecorators($decorator);
        $submit->setAttrib('class','btn btn-success');
        $form->addElement($submit);
		
		$cancel=$form->createElement('submit','cancel');
		$cancel->setLabel('Cancel');
        $cancel->setAttrib('class','btn btn-danger');
        $cancel->setDecorators($decorator);
		$form->addElement($cancel);
		
		return $form;
	}
	public function createReply(){
		
		$form=new Zend_Form();
		$form->setMethod('POST');

		$reply=$form->createElement('textarea', 'reply');
		$reply->setLabel('Reply: ');
		$reply->setRequired(true);
		$reply->setAttrib('rows',15);
		$reply->setAttrib('cols',44);
		$reply->addValidator(new Zend_Validate_StringLength(0,500));
		$reply->addErrorMessage('Reply must not be empty');
		$form->addElement($reply);
		
		$decorator = array(
						array('ViewHelper'),
						array('HtmlTag', array('tag' => 'span')),
						//array('Label', array('escape' => false))
					);
	
		$submit=$form->createElement('submit','submit');
		$submit->setLabel('Submit: ');
		$submit->setDecorators($decorator);
		$form->addElement($submit);
		
		$cancel=$form->createElement('submit','cancel');
		$cancel->setLabel('Cancel');
		$cancel->setDecorators($decorator);
		$form->addElement($cancel);
		
		return $form;

	}

}