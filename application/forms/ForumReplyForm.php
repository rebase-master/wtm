  <?php
class Form_ForumReplyForm extends Zend_Form{
    public function init(){
    	$this->setMethod('POST');

		$reply=$this->createElement('textarea', 'reply');
		$reply->setLabel('');
		$reply->setRequired(true);
		$reply->setAttrib('rows',15);
		$reply->setAttrib('cols',100);
		$reply->addValidator(new Zend_Validate_StringLength(1,1000));
		$reply->addErrorMessage('Reply cannot be empty and must not exceed 1000 characters.');
		$this->addElement($reply);
		
		$decorator = array(
						array('ViewHelper'),
						array('HtmlTag', array('tag' => 'span')),
						//array('Label', array('escape' => false))
					);
	
		$submit=$this->createElement('submit','submit');
		$submit->setLabel('Submit');
		$submit->setDecorators($decorator);
		$submit->setAttrib('class', 'btn btn-success');
        $this->addElement($submit);
		
		$cancel=$this->createElement('submit','cancel');
		$cancel->setLabel('Cancel');
        $cancel->setAttrib('class','btn btn-danger');
		$cancel->setDecorators($decorator);
		$this->addElement($cancel);

	}

}