<?php
class Form_Programs extends Zend_Form{
    public function init(){

        $this->setMethod('POST');
        $this->setAttrib('class','form-horizontal');

        $topic=$this->createElement('select','topic_id');
        $topic->setLabel('Select a topic: ');
        $topic->setRequired(true);
        $topic->setAttrib('class', 'form-control');
        $mdlTopics = new Model_Topics();
        $topics = $mdlTopics->readTopics();
        foreach($topics as $sub_topic){
            $topic->addMultiOption($sub_topic->id, $sub_topic->topic);
        }
        
        $this->addElement($topic);
        
        $heading = $this->createElement('text', 'heading');
	    $heading->setLabel('Heading: ');
	    $heading->setRequired(false);
	    $heading->setAttrib('class', 'form-control');
        $this->addElement($heading);
        
        $slug = $this->createElement('text', 'slug');
	    $slug->setLabel('Slug: ');
	    $slug->setRequired(true);
	    $slug->setAttrib('class', 'form-control');
        $this->addElement($slug);
        
        $question = $this->createElement('textarea', 'question');
        $question->setLabel('Question: ');
        $question->setRequired(TRUE);
        $question->setAttrib('class', 'form-control');
	    $question->setAttrib('id', 'question');
	    $this->addElement($question);

        $solution = $this->createElement('textarea', 'solution');
	    $solution->setAttrib('class', 'form-control');
	    $solution->setAttrib('id', 'solution');
	    $solution->setLabel('Solution: ');
        $solution->setRequired(false); 
        $this->addElement($solution);

	    $visible=$this->createElement('select','visible');
	    $visible->setLabel('Visible: ');
	    $visible->addMultiOption(1,'Yes');
	    $visible->addMultiOption(0,'No');
	    $visible->setAttrib('class', 'form-control');
	    $this->addElement($visible);

	    $submit = $this->addElement('submit', 'submit', array('label' => 'Submit', 'class' => 'btn btn-primary'));
    }
}