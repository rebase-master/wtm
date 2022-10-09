  <?php
class Form_ConfirmForm extends Zend_Form{
    public function init(){
	
	$this->setMethod('POST');
	$id=$this->createElement('hidden','id');
	$id->setDecorators(array('ViewHelper'));
	$this->addElement($id);

	$decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

	$submit=$this->createElement('submit','confirm');
	$submit->setLabel('Yes');
	$submit->setDecorators($decorator);
    $submit->setAttrib('class','btn btn-danger');
    $this->addElement($submit);
	
	$cancel=$this->createElement('submit','cancel');
	$cancel->setLabel('No');
    $cancel->setAttrib('class','btn btn-success');
	$cancel->setDecorators($decorator);
	$this->addElement($cancel);
	
	}
}