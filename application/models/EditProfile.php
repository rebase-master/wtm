<?php
class Model_ProfileOptions extends Zend_Db_Table_Abstract{
    //override default table name
    protected $_name='profile_options';
	protected $_cols = array('id','options');

	//the user class' row model
    //protected $_rowClass='UserRow';
    
    public function getUserByUsername($username){
        $select=$this->select()
                ->where('username=?',$username);
        $row=$this->fetchRow($select);
        if($row){
        return $row;
        }else{
            return null;
        }
    }
    public function getUserCount(){
        $select=$this->select('id');
        $result=$this->fetchAll($select);
        
        return count($result);
    }
    
}