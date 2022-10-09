<?php
class Model_QuizCategory extends Zend_Db_Table_Abstract{
    
    protected $_name='quiz_category';
	protected $_cols = array('id','category');

	public function findId($categoryName){
    $select=$this->select()
            ->from($this->_name, array('id'))
            ->where('category=?',$categoryName);
    $result=$this->fetchRow($select);
    return count($result)>0?$result->id:null;
    }
    public function getCategories(){
        $select=$this->select()
                ->from($this->_name, 'category');
        $result=$this->fetchAll($select);
        return $result;
    }
}