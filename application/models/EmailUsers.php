<?php
class Model_EmailUsers extends Zend_Db_Table_Abstract{
    protected $_name = 'email_users';
    protected $_cols = array('id','email','frequency','created');
    
    public function emailSent($email){
        $select=$this->select()
                ->where('email=?',$email);
        $result=$this->fetchRow($select);
        if($result){
            $result->frequency = intval($result->frequency) + 1;
	        $result->created = date('Y-m-d H:i:s', time()+5.5*60*60);
	        $result->save();
        }else{
        $row=$this->createRow();
        if($row){
            $row->email=$email;
            $row->frequency=$row->frequency+1;
            $row->save();
        }
        }
    }
    public function emailFreq($email){
        $select=$this->select()
                ->where('email=?',$email);
        $result=$this->fetchRow($select);
        return $result['frequency'];
    }
}