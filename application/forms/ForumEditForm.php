  <?php
class Form_ForumEditForm extends Zend_Form{
    public function init(){
    	$this->setMethod('POST');
		
		$categoryList=$this->createElement('select', 'category')
					   ->setRequired(true)
					   ->setLabel('Select Subject:');

		$this->addElement($categoryList);
		
		$topic=$this->createElement('select', 'topic')
					   ->setLabel('Select Topic:');
		$this->addElement($topic);
		$this->addElement('submit','submit',array('label'=>'Submit'));
	}
}
