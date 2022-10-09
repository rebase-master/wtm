<?php
class Model_QAAnswers extends Zend_Db_Table_Abstract{
	protected $_name = 'qa_answers';
	protected $_cols = array('id','pid','uid','answer','visible','created');
  
  public function add($pid,$uid,$answer){
    $row=$this->createRow();
	if($row){
	$row->pid = $pid;
	$row->uid = $uid;
	$row->answer = $answer;
    $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
    $row->save();
    return $row;
	}else{
	  return false;
	}
  }

  public function getAnswers($pid){
  $select=$this->select()
              ->setIntegrityCheck(false)
              ->from(array('a'=>$this->_name))
              ->join(array('b'=>'users'), 'a.uid=b.id', array('username','gender'))
              ->joinLeft(array('c'=>'user_data'), 'a.uid=c.user_id && c.option_id=11', array('data'))
			  ->where('pid=?',$pid)
			  ->where('visible=\'1\'')
			  ->order('created ASC');
        $sql = "SELECT d.upvotes, e.downvotes, d.vote, a.*, b.username, b.gender, c.data as dp
                FROM qa_answers AS a 
                INNER JOIN users AS b ON a.uid=b.id 
                LEFT JOIN user_data AS c ON a.uid=c.user_id && c.option_id=11
                LEFT JOIN(
                          SELECT cid, vote, COUNT(1) upvotes
                             FROM qa_answer_votes
                             where vote = '1'
                             GROUP BY cid
                        ) d
                     ON a.id = d.cid
                LEFT JOIN(
                          SELECT cid, vote, COUNT(1) downvotes
                             FROM qa_answer_votes
                             where vote = '-1'
                             GROUP BY cid
                        ) e
                     ON a.id = e.cid
                WHERE (pid=".$pid.") AND (visible='1')
                ORDER BY created ASC";
      
      $row=$this->getAdapter()->fetchAll($sql);
      //return $select;
      return $row;
  }
  public function removeAnswer($cid, $uid){
	$data=array('visible'=>'0');
	$where=$this->getAdapter()->quoteInto('id=?',$cid);
	$r=$this->update($data,$where);
	return $r;
  }
  public function getNoOfUpvotes($pid){
	$select=$this->select()
				->from($this->_name, 'COUNT(*) as num')
				->where('pid=?',$pid)
				->where('vote=\'1\'');
	$result=$this->fetchRow($select);
	return count($result)>0?$result->num:0;
  }
}