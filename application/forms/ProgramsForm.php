<?php
class Form_Programs extends Zend_Form{
    public function init(){
        $this->setMethod('POST');
        
        $password1=$this->createElement('password','password1');
        $password1->setLabel('Your New Password: ');
        $password1->setRequired(true);
        $password1->setAttrib('size','25');
        $password1->addValidator(new Zend_Validate_StringLength(6,20));
        $form->addElement($password1);
        
        $password2=$form->createElement('password','password2');
        $password2->setLabel('Confirm Your New Password: ');
        $password2->setRequired(true);
        $password2->setAttrib('size','25');
        $password2->addValidator(new Zend_Validate_StringLength(6,20));
        $form->addElement($password2);
        
        $this->addElement('submit', 'submit', array('label' => 'Reset Password'));
    }
}