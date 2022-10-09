<?php
class Model_QAQuestions extends Zend_Db_Table_Abstract{
    
    protected $_name = 'qa_questions';
	protected $_cols = array('id','uid','topic','question','description','created','visible');
    
    public function addQuestion($data){
        $row=$this->createRow();
        if($row){
            $row->uid = $data['uid'];
            $row->topic = 0;
            $row->tags = $data['tags'];
            $row->question = $data['question'];
            $row->description = $data['description'];
            $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);

            return $row->save();
        }else{
            return false;
        }
    }

    public function findById($id){
        $sql = "SELECT d.upvotes, e.downvotes, a.*, b.username, b.gender, b.first_name, b.last_name, b.email, c.data as dp
                FROM qa_questions AS a
                INNER JOIN users AS b ON a.uid=b.id
                LEFT JOIN user_data AS c ON a.uid=c.user_id && c.option_id=11
                LEFT JOIN(
                          SELECT qid, vote, COUNT(1) upvotes
                             FROM qa_question_votes
                             where vote = '1'
                             GROUP BY qid
                        ) d
                     ON a.id = d.qid
                LEFT JOIN(
                          SELECT qid, vote, COUNT(1) downvotes
                             FROM qa_question_votes
                             where vote = '-1'
                             GROUP BY qid
                        ) e
                     ON a.id = e.qid
                WHERE (a.id=".$id.") AND (visible='1')
                ";

        try{
            $row = $this->getAdapter()->fetchRow($sql);
        }catch(Exception $e){
            Phototour_Logger::log($e);
            $row = false;
        }
        return $row;
    }
  public function findQuesByTag($tag){
//        $select = $this->select()->setIntegrityCheck(false)
//        ->from(array('a'=>'qa_questions'))
//        ->join(array('b'=>'users'), 'a.uid=b.id', array('username','gender'))
////        ->join(array('c'=>'user_data'), 'a.uid=c.user_id && c.option_id=11', array('data'))
//        ->where('visible=\'\1\'')
//        ->where('INSTR(tags, ?) > 0', $tag)
//        ->order('created DESC')
//        ->limit(50);
        $sql = "SELECT c.answer_count, a.*, b.username, b.gender, d.data as dp
                FROM qa_questions AS a 
                JOIN users AS b ON a.uid=b.id
                LEFT JOIN user_data as d on a.uid = d.user_id and d.option_id=11
                LEFT JOIN(
                SELECT * FROM
                  (
                     SELECT pid,COUNT(1) answer_count
                     FROM qa_answers
                     GROUP BY pid
                     ORDER BY answer_count DESC
                   ) aa
                ) c
                 ON a.id = c.pid
                WHERE (visible='1') AND (INSTR(tags, '".$tag."') > 0)
                ORDER BY a.created DESC
                LIMIT 50";
        try{
            $result=$this->getAdapter()->fetchAll($sql);
        }catch(Exception $e){
            Phototour_Logger::log($e);
            $result = false;
        }
      return $result;
  
  }
    public function recentQuestions($sorter = null){
        $select = $this->select()->setIntegrityCheck(false)
        ->from(array('a'=>'qa_questions'))
        ->join(array('b'=>'users'), 'a.uid=b.id', array('username','gender'))
        //->join(array('c'=>'user_data'), 'a.uid=c.user_id && c.option_id=11', array('data'))
        //->join(array('d'=>'qa_answers'),'a.id=d.pid')
        ->where('visible=\'1\'')
        ->order('created DESC')
        ->limit(50);
//        $result=$this->fetchAll($select);

        $sql = "SELECT a.*, b.answer_count, e.vote_count, c.username, c.gender, d.data as dp
                FROM qa_questions a
                LEFT JOIN users as c on a.uid = c.id
                LEFT JOIN user_data as d on a.uid = d.user_id and d.option_id=11
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT pid,COUNT(1) answer_count
                        FROM qa_answers
                        GROUP BY pid
                        ORDER BY answer_count DESC
                    ) AA
                ) b
                ON a.id = b.pid
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT qid,COUNT(1) vote_count
                        FROM qa_question_votes
                        GROUP BY qid
                    ) AA
                ) e
                ON a.id = e.qid
                where a.visible = '1'
                group by a.id
                order by a.created DESC
              ";
        $result=$this->getAdapter()->fetchAll($sql);
        return $result;
    }

    public function popularQuestions(){
        $sql = "SELECT a.*, b.answer_count, e.vote_count, c.username, c.gender, d.data as dp
                FROM qa_questions a
                LEFT JOIN users as c on a.uid = c.id
                LEFT JOIN user_data as d on a.uid = d.user_id and d.option_id=11
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT pid,COUNT(1) answer_count
                        FROM qa_answers
                        GROUP BY pid
                        ORDER BY answer_count DESC
                    ) AA
                ) b
                ON a.id = b.pid
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT qid,COUNT(1) vote_count
                        FROM qa_question_votes
                        GROUP BY qid
                    ) AA
                ) e
                ON a.id = e.qid
                where a.visible = '1'
                group by a.id
                order by b.answer_count DESC
                limit 0,50
              ";
        $result = $this->getAdapter()->fetchAll($sql);
          //return $select;
          return $result;
    }

    public function unansweredQuestions(){
        $sql = "SELECT a.*, e.vote_count, b.username, b.gender, d.data as dp
                FROM qa_questions as a join users as b on a.uid = b.id
                LEFT OUTER JOIN qa_answers as c on a.id = c.pid
                LEFT JOIN user_data as d on a.uid = d.user_id and d.option_id=11
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT qid,COUNT(1) vote_count
                        FROM qa_question_votes
                        GROUP BY qid
                    ) AA
                ) e
                ON a.id = e.qid
                where (c.pid IS NULL and a.visible = '1')
                group by a.id
                order by created DESC";
        $result = $this->getAdapter()->fetchAll($sql);
        return $result;
    }

    public function relatedQuestions($tags, $id){
        $tag = explode(",",$tags);
        $sql = "SELECT a.id, b.vote_count, a.question FROM qa_questions as a
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT qid,COUNT(1) vote_count
                        FROM qa_question_votes
                        GROUP BY qid
                    ) AA
                ) b
                ON a.id = b.qid
                where (a.id != $id and a.tags LIKE '%".$tag[0]."%' and a.visible = '1')
                group by a.id
                order by b.vote_count DESC
                limit 0,5";
        $result = $this->getAdapter()->fetchAll($sql);
        return $result;
    }

     public function removeQuestion($pid, $uid){
        $data=array('visible'=>'0');
        $where=$this->getAdapter()->quoteInto('id=?',$pid);
        $r = $this->update($data,$where);
	     try{
		     return $r;
	     }catch (Exception $e){
		     $log = new Zend_Log(
			     new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		     );
		     $log->debug($e->getTraceAsString());

		     return -1;
	     }
    }

    public function deleteProgram($id)
    {
        // find the row that matches the id
        $row = $this->find($id)->current();
        if ($row) {
            $row->delete();
            return true;
        } else {
            throw new Zend_Exception("Delete function failed; could not find page!");
        }
    }
    public function updateProgram($id,$data){
        $row=$this->find($id)->current();
        if($row){
            $row->question=$data['question'];
            $row->solution=$data['solution'];
            $row->save();
            unset($data['question']);
            unset($data['solution']);
        }
    }
}