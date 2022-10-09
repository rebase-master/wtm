  <?php
class Form_Results extends Zend_Form{
    public function init(){
	
	$this->setMethod('POST');
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);
	
		$class=$this->createElement('select','class');
	$class->setLabel('Class: ');
	$class->setAttrib('cols',15);
	//$class->addValidator(new Zend_Validate_StringLength(0,20));
	//$class->addErrorMessage('Value must not exceed 20 characters');
	//$class->addFilter('StripTags');
	  $values=array(
		'10'=>'10',
		'12'=>'12',
	  );
	  foreach($values  as $key => $value){
        $class->addMultiOption($key, $value);    
        }

	$this->addElement($class);

	$centre=$this->createElement('text','centre');
	$centre->setLabel('Centre No: ');
	$centre->setAttrib('size',10);
	$centre->setRequired(true);
	$centre->addValidator(new Zend_Validate_Int());
	$centre->addErrorMessage('Value must be an integer');
	$this->addElement($centre);
	
	$rollNo=$this->createElement('text','roll_no');
	$rollNo->setLabel('Roll No: ');
	$rollNo->setAttrib('size',10);
	$centre->setRequired(true);
	$rollNo->addValidator(new Zend_Validate_Int());
	$rollNo->addErrorMessage('Value must be an integer');
	$this->addElement($rollNo);

	$email=$this->createElement('text','email');
	$email->setLabel('E-mail: ');
	$email->addValidator(new Zend_Validate_EmailAddress());
	$email->addErrorMessage('Please enter a valid e-mail address. E.g. bhagatsingh@india.com');
	$email->addFilter('StripTags');
	$this->addElement($email);

	$decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

	$submit=$this->createElement('submit','submit');
	$submit->setLabel('Submit');
	$submit->setDecorators($decorator);
	$this->addElement($submit);
	
	//$cancel=$this->createElement('submit','cancel');
	//$cancel->setLabel('Cancel');
	//$cancel->setDecorators($decorator);
	//$this->addElement($cancel);
	
	}
}