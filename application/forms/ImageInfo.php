<?php
class Form_ImageInfo extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
	$this->setDecorators(array(
    'FormElements',
    array('HtmlTag', array('tag' => 'table')),
    'Form',
	));
	
	$decorators=array(
            'ViewHelper',
            'Description',
            array(array('data'=>'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'th')),
            array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
       );
	$errorDecorators=array(
            'ViewHelper',
            'Errors',
            array(array('data'=>'HtmlTag'), array('tag' => 'td')),
            //array('Label', array('tag' => 'th')),
            //array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
       );
        
		
        $name = $this->createElement('text', 'name');
        // element options
        $name->setLabel('Image name: ');
        $name->setRequired(true);
		$name->setDecorators($decorators);
        $this->addElement($name);
	
        $description = $this->createElement('textarea', 'description');
        // element options
        $description->setLabel('Image description: ');
        $description->setRequired(true); 
        $description->setAttrib('cols',60); 
        $description->setAttrib('rows',14);
		$description->setDecorators($decorators);
        // add the element to the form
        $this->addElement($description);
       
	    $source = $this->createElement('textarea', 'source');
        // element options
        $source->setLabel('Source: ');
        $source->setAttrib('cols',60);
		$source->setAttrib('rows',3);
		$source->setDecorators($decorators);
        $this->addElement($source);
	
    $decorator = array(
				array('ViewHelper'),
				array('HtmlTag', array('tag' => 'span')),
				//array('Label', array('escape' => false))
				);

  $save=$this->createElement('submit','save')
				  ->setLabel('Save');
  				  //->setAttrib('tabIndex', '3');
  $save->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data'=>'HtmlTag'), array('tag'=>'tr', 'tag' => 'td',
												  'align'=>'right')),
       ));
  $reset=$this->createElement('submit','reset')
				  ->setLabel('Reset');
  				  //->setAttrib('tabIndex', '3');
  $reset->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data'=>'HtmlTag'), array('tag'=>'tr', 'tag' => 'td',
												  'align'=>'left')),
       ));
  $this->addElement($save);
  $this->addElement($reset);
    }
 }