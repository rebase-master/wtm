<?php
class Model_QuizScores extends Zend_Db_Table_Abstract{
    
    protected $_name='quiz_scores';
	protected $_cols = array('id','qid','uid','score','attempts','created');

	public function save($qid, $uid,$score){
    $select=$this->select()
            ->where('qid=?',$qid)
            ->where('uid=?',$uid);
    
    $found=$this->fetchRow($select);
    if(count($found)>0){
        $found->score=$score;
        $found->attempts=$found->attempts+1;
	    $found->created = date('Y-m-d H:i:s', time()+5.5*60*60);
        return $found->save();
    }else{
    $row=$this->createRow();
    $row->qid=$qid;
    $row->uid=$uid;
    $row->score=$score;
    $row->created=date('Y-m-d H:i:s', time()+5.5*60*60);
    return $row->save();
    }
    }
    public function highscores($qid){
        $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                    ->setIntegrityCheck(false)
                    ->from(array('a'=>$this->_name), array('uid','score','attempts'))
                    ->join(array('b'=>'users'), 'a.uid=b.id', array('username'))
                    ->where('qid=?',$qid)
                    ->order('a.score DESC')
                    ->limit(10);
        $result=$this->fetchAll($select);
        return $result;
        //return $select;
    }
    public function quizTakers(){
        $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                    ->setIntegrityCheck(false)
                    ->from(array('a'=>$this->_name), array('score','attempts','created'))
                    ->join(array('b'=>'users'), 'a.uid=b.id', array('username'))
                    ->join(array('c'=>'quiz_category'),'a.qid=c.id',array('category'))
                    ->order('a.created DESC');
        $result=$this->fetchAll($select);
        return $result;
        //return $select;
    }

}