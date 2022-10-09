  <?php
class Form_ForumTopicForm extends Zend_Form{
    public function init(){
    	$this->setMethod('POST');
		
		$heading=$this->createElement('text','topic_name');
		$heading->setLabel('Topic Name:');
		$heading->setAttrib('size',50);
		$heading->setRequired(true);
		$this->addElement($heading);
		
		$this->addElement('submit','submit', array('label'=>'Create Topic'));
		
	}
}