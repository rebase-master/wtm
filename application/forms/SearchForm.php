  <?php
class Form_SearchForm extends Zend_Form{
    public function init(){
	
	$this->setMethod('POST');
	//$id=$this->createElement('hidden','id');
	//$id->setDecorators(array('ViewHelper'));
	//$this->addElement($id);

	$decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

	$cancel=$this->createElement('submit','go');
	$cancel->setLabel('GO!');
	$cancel->setDecorators($decorator);
	$this->addElement($cancel);

	$submit=$this->createElement('text','search');
	//$submit->setLabel('');
	$submit->setAttrib('placeholder','search');
	$submit->setDecorators($decorator);
	$this->addElement($submit);
	
	}
}