<?php
class Model_JavaQuizQuestions extends Zend_Db_Table_Abstract{
    
    protected $_name='java_quiz_questions';
    protected $_cols=array('id','level','question','answer','created');
    //level- 0->beginner, 1->intermediate, 2->advanced
    
    public function add($level,$question,$answer){
        $row=$this->createRow();
        if($row){
            $row->level=$level;
            $row->question=$question;
            $row->answer=$answer;
            $row->created=date('Y-m-d H:i:s', time()+5.5*60*60);
            return $row->save();
        }else{
            return null;
        }
    }
    public function updateQuestion($id,$level,$question,$answer){
        //$id=$this->find($id);
        $data=array('level'=>$level, 'question'=>$question, 'answer'=>$answer);
        $where=$this->getAdapter()->quoteInto('id=?',$id);
        $r=$this->update($data,$where);
        return $r;
    }
    public function deleteQuestion($id){
      $where=$this->getAdapter()->quoteInto('id=?',$id);
      return $this->delete($where);
    }
    public function getAllQuestions(){
    $select=$this->select()
                 ->order('created DESC');
    $result=$this->fetchAll($select);
    return count($result)>0?$result:null;
    }
    public function getQuesByLevel($level){
    $select=$this->select()
                ->from($this->_name,array('question','created'))
                ->where('level=?',$level);
    $result=$this->fetchAll($select);
    return count($result)>0?$result:null;
    }
    public function getQuestionSetByLevel($level,$limit=10){
        $select=$this->select()
                ->from(array('a'=>$this->_name), array('id','question','answer'))
                ->where('a.level=?',$level)
                ->order(new Zend_Db_Expr('RAND()'))
                ->limit($limit);
        //return $select;
        
        $result=$this->fetchAll($select);
        return count($result)>0?$result:null;
    }
    public function getJavaQuestion($id){
        $select=$this->select()
                ->where('id=?',$id)
                ->limit(1);
        $result=$this->fetchRow($select);
        return count($result)>0?$result:null;
    }
    public function quesInLevel($level){
        if(strcasecmp($level,"beginner")==0){
            $level='0';
        }elseif(strcasecmp($level,"intermediate")==0){
            $level='1';
        }elseif(strcasecmp($level,"advanced")==0){
            $level='2';
        }
    $select=$this->select()
                ->from($this->_name, 'COUNT(*) as num')
                ->where('level=?',$level);
    $result=$this->fetchRow($select);
    return count($result)>0?$result->num:null;
    }
}