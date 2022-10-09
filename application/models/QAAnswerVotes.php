<?php
class Model_QAAnswerVotes extends Zend_Db_Table_Abstract{
	protected  $_name = 'qa_answer_votes';
	protected $db_fields = array('id','cid','uid','vote','created');
  
  public function add($cid,$uid){
	$result=$this->exists($cid,$uid);
	if(!$result){
    $row=$this->createRow();
	if($row){
		$row->cid=$cid;
		$row->uid=$uid;
		$row->vote=1;
		$row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
		$row->save();
    return $row;
	}else{
	  return false;
	}
	}else{
	  $result->vote=1;
	  return $result->save();
	}
  }
  public function subtract($cid,$uid){
	$result=$this->exists($cid,$uid);
	if(!$result){
    $row=$this->createRow();
	if($row){
	$row->cid=$cid;
	$row->uid=$uid;
	$row->vote=-1;
    $row->save();
    return $row;
	}else{
	  return false;
	}
	}else{
	  $result->vote=-1;
	  return $result->save();
	}
  }
  public function exists($cid,$uid){
	$select=$this->select()
			->where('cid=?',$cid)
			->where('uid=?',$uid)
			->limit(1);
	$result=$this->fetchRow($select);
	return count($result)>0?$result:false;
  }
  public function totalUpVotes($cid){
	$select=$this->select()
		->from($this->_name, array('COUNT(*) as plus'))
		->where('cid=?',$cid)
		->where('vote=\'1\'');
	$result=$this->fetchRow($select);
  return $result->plus;
  }
  public function totalDownVotes($cid){
	$select=$this->select()
			->from($this->_name, array('COUNT(*) as minus'))
			->where('cid=?',$cid)
			->where('vote=\'-1\'');
	$result=$this->fetchRow($select);
  return $result->minus;
  }
  public function totalCommentVotes($cid){
	$totalVotes=$this->totalUpVotes($cid) - $this->totalDownVotes($cid);
	return $totalVotes;
  }
    public function userVotes($uid){
        $sql = "SELECT cid, vote FROM $this->_name where uid = ".$uid;
        $result = $this->getAdapter()->fetchPairs($sql);

        return $result;
    }
}