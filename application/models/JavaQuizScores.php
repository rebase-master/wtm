<?php
class Model_JavaQuizScores extends Zend_Db_Table_Abstract{
    
    protected $_name = 'java_quiz_scores';
	protected $_cols = array('id','uid','category','score','attempts','created');
    //category- beginner, intermediate, advanced
    
    public function save($uid,$category,$score){
    $select=$this->select()
            ->where('category=?',$category)
            ->where('uid=?',$uid);
    
    $found = $this->fetchRow($select);
    if(count($found)>0){
        if($found->score<$score){
            $found->score=$score;
        }
        $found->attempts = intval($found->attempts)+1;
        return $found->save();
    }else{
	    $row=$this->createRow();
	    $row->uid=$uid;
	    $row->category=$category;
	    $row->score=$score;
	    $found->attempts=1;
	    $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
	    return $row->save();
    }
    }
    public function highscores($category){
        $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                    ->setIntegrityCheck(false)
                    ->from(array('a'=>$this->_name), array('uid','score','attempts'))
                    ->join(array('b'=>'users'), 'a.uid=b.id', array('username'))
                    ->where('category=?',$category)
                    ->order('a.score DESC')
                    ->limit(10);
        $result=$this->fetchAll($select);
        return count($result)>0?$result:null;
        //return $select;
    }
    public function quizTakers(){
        $select=$this->select(Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
                    ->setIntegrityCheck(false)
                    ->from(array('a'=>$this->_name), array('score','category','attempts','created'))
                    ->join(array('b'=>'users'), 'a.uid=b.id', array('username'))
                    ->order('a.created DESC');
        $result=$this->fetchAll($select);
        return $result;
        //return $select;
    }
    public function quizTakersByCategory($category){
        $select=$this->select()
                    ->from($this->_name, array('COUNT(*) as num'))
                    ->where('category=?',$category);
        $result=$this->fetchRow($select);
        return $result->num;
        //return $select;
    }
}