<?php
class Model_ProfileOptions extends Zend_Db_Table_Abstract{

    protected $_name='profile_options';
	protected $_cols = array('id','options');

    public function getIdByOptionName($optionName){
        //$row=$this->find($optionName)->current();
        $select=$this->select('id');
        $select->where("options = ?", $optionName);
        $row=$this->fetchRow($select);
        //return $select;
        if($row){
        return $row;
        }else{
            return null;
        }
    }
    
}