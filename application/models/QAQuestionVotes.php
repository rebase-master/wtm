<?php
class Model_QAQuestionVotes extends Zend_Db_Table_Abstract{
    protected  $_name = 'qa_question_votes';
	protected $_cols = array('id','qid','uid','vote','created');
  
  public function add($qid,$uid){
	$result=$this->exists($qid,$uid);
	if(!$result){
    $row=$this->createRow();
	if($row){
		$row->qid = $qid;
		$row->uid = $uid;
		$row->vote = 1;
		$row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
		$row->save();
    return $row;
	}else{
	  return false;
	}
	}else{
	  $result->vote = 1;
	  return $result->save();
	}
  }

  public function subtract($qid,$uid){
	$result=$this->exists($qid,$uid);
	if(!$result){
    $row=$this->createRow();
	if($row){
	$row->qid = $qid;
	$row->uid = $uid;
	$row->vote = -1;
    $row->save();
    return $row;
	}else{
	  return false;
	}
	}else{
	  $result->vote = -1;
	  return $result->save();
	}
  }

  public function exists($qid,$uid){
	$select=$this->select()
			->where('qid=?',$qid)
			->where('uid=?',$uid)
			->limit(1);
	$result=$this->fetchRow($select);
	return count($result)>0?$result:false;
  }

  public function totalUpVotes($qid){
	$select=$this->select()
		->from($this->_name, array('COUNT(*) as plus'))
		->where('qid=?',$qid)
		->where('vote=\'1\'');
	$result=$this->fetchRow($select);
  return $result->plus;
  }

  public function totalDownVotes($qid){
	$select=$this->select()
			->from($this->_name, array('COUNT(*) as minus'))
			->where('qid=?',$qid)
			->where('vote=\'-1\'');
	$result=$this->fetchRow($select);
  return $result->minus;
  }

  public function totalCommentVotes($qid){
	$totalVotes=$this->totalUpVotes($qid) - $this->totalDownVotes($qid);
	return $totalVotes;
  }
    public function userVotes($uid){
        $sql = "SELECT qid, vote FROM $this->_name where uid = ".$uid;
        $result = $this->getAdapter()->fetchPairs($sql);

        return $result;
    }

}