<?php
class Model_JavaQuizOptions extends Zend_Db_Table_Abstract{
    
    protected $_name = 'java_quiz_options';
    private $_cols = array('id','qid','options');
    
    public function add($qid,$options){
        foreach($options as $opt){
        $row=$this->createRow();
        $row->qid=$qid;
        $row->options=$opt;
        $row->save();
        }
    }

    public function updateOptions($qid,$options){

        $select=$this->select()
                ->where('qid=?',$qid);
        $results=$this->fetchAll($select);
        if(count($results)>0){
            $i=0;
            foreach($results as $result){
                $result['options']=$options[$i++];
                $result->save();
            }
            return true;
        }else{
        return null;            
        }
    }
  public function deleteOptions($qid){
	$where=$this->getAdapter()->quoteInto('qid=?',$qid);
	return $this->delete($where);
  }
    public function getOptionsByQues($questionId){
    $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name), array('options'))
                ->joinRight(array('b'=>'java_quiz_questions'),'b.id=a.qid', null)
                ->where('b.id=?',$questionId);
    $db=Zend_Db_Table::getDefaultAdapter();
    $stmt=$db->prepare($select);
    //$stmt=$db->
    //$result=$stmt->execute();
    $result=$this->fetchAll($select);
    return $result;
    //return $select;
    
    }
}