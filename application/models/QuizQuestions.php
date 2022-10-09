<?php
class Model_QuizQuestions extends Zend_Db_Table_Abstract{
    
    protected $_name='quiz_questions';
	protected $_cols = array('id','cat_id','question','answer','created');

    public function addQuestion($catId, $question, $answer){
        $row=$this->createRow();
        $row->cat_id=$catId;
        $row->question=$question;
        $row->answer=$answer;
        $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
        return $row->save();
    }
    public function getQuesByCategory($category){
    $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name), array('id','question','answer'))
                ->joinRight(array('b'=>'quiz_category'),'b.id=a.cat_id',null)
                ->where('b.category=?',$category)
                ->order(new Zend_Db_Expr('RAND()'))
                ->limit(10);
    $result=$this->fetchAll($select);
    return count($result)>0?$result:null;
    //return $select;
    
    }
    public function countQuesByCategory($category){
        $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                    ->setIntegrityCheck(false)
                    ->from(array('a'=>$this->_name), 'COUNT(*) AS num')
                    ->join(array('b'=>'quiz_category'),'b.id=a.cat_id',null)
                    ->where('category=?',$category);
        $result=$this->fetchRow($select);
        return $result->num;
        //return $select;
    }
	public function count(){
		$select=$this->select()
			->from($this->_name, 'COUNT(*) AS num');
		$result=$this->fetchRow($select);
		return $result->num;
	}

}