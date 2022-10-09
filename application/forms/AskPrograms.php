<?php
class Form_AskPrograms extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        $this->setAttrib('class', 'form-horizontal');

        $topic=$this->createElement('select','topic');
        $topic->setLabel('Select a topic: ');
        $topic->setRequired(true);
        $topic->setAttrib('class', 'form-control');
        $topicsOptions=array(
            1 => 'Loops - Series',
            2 => 'Loops - Patterns',
            3 => 'Loops - other',
            4 => 'Arrays 1-D',
            5 => 'Arrays 2-D',
            6 => 'Strings',
            7 => 'Functions',
            8 => 'Recursive Functions',
            9 => 'Classes and Objects',
            10 => 'Objects as parameter',
            11 => 'Stack/Queue/Dequeue/Circular Queue',
            12 => 'Linked Lists'
        );
        
        foreach($topicsOptions as $key => $value){
        $topic->addMultiOption($key, $value);
        }
        
        $this->addElement($topic);
        
        $question = $this->createElement('textarea', 'program_question');
        // element options
        $question->setLabel('Question: (Give a short summary of your problem) ');
        $question->setRequired(TRUE); 
        $question->setAttrib('class', 'form-control');
        // add the element to the form
        $this->addElement($question);

        $question_desc = $this->createElement('textarea', 'description');
        // element options
        $question_desc->setLabel('Description: (Describe your problem here) ');
        $question_desc->setRequired(TRUE); 
        $question_desc->setAttrib('class', 'form-control');
        // add the element to the form
        $this->addElement($question_desc);

        $submit= $this->createElement('submit','submit');
        $submit->setLabel('Submit');
        $submit->setAttrib('class', 'btn btn-success btn-lg');
        $this->addElement($submit);
    }
}