<?php
class Model_QuizOptions extends Zend_Db_Table_Abstract{
    
    protected $_name='quiz_options';
	protected $_cols = array('id','qid','options','created');
	
    public function addOptions($qid,$options){
        foreach($options as $opt){
        $row=$this->createRow();
        $row->qid=$qid;
        $row->options=$opt;
        $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
        $row->save();
        }
    }
    public function getOptionsByQues($questionId){
    $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name), array('options'))
                ->joinRight(array('b'=>'quiz_questions'),'b.id=a.qid', null)
                ->where('b.id=?',$questionId);
    $result=$this->fetchAll($select);
    return $result;
    //return $select;
    
    }
}