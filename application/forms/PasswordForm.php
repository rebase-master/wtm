<?php
class Form_PasswordForm extends Zend_Form{
    public function init(){
        $this->setMethod('POST');

        $password1=$this->createElement('password','password1');
        $password1->setLabel('New Password: ');
        $password1->setRequired(true);
        $password1->setAttrib('size','25');
        $password1->setAttrib('class','form-control');
        $password1->addValidator(new Zend_Validate_StringLength(6,20));
        $this->addElement($password1);
        
        $password2=$this->createElement('password','password2');
        $password2->setLabel('Confirm New Password: ');
        $password2->setRequired(true);
        $password2->setAttrib('size','25');
        $password2->setAttrib('class','form-control');
        $password2->addValidator(new Zend_Validate_StringLength(6,20));
        $this->addElement($password2);

        $submit= $this->createElement('submit','submit');
        $submit->setLabel('Reset Password');
        $submit->setAttrib('class', 'btn btn-primary btn-lg');
        $this->addElement($submit);
    }
}