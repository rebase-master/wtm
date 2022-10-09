<?php
class Model_YearlyQuestionTopics extends Zend_Db_Table_Abstract{
    
    protected $_name = 'yearly_question_topics';
    protected $_cols = array('id','question_id','topic_id','created');

    public function createQuestionTopics($questionId, $topicIds){

	    foreach($topicIds as $topicId){

		    $row                =   $this->createRow();
		    $row->question_id   =   intval($questionId);
		    $row->topic_id      =   intval($topicId);
		    $row->created       =   date('Y-m-d H:i:s', time()+5.5*60*60);

		    try{
			    $row->save();
		    }catch (Exception $e){
			    $log = new Zend_Log(
				    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
			    );

			    $log->log("ERROR: ", $e->getTraceAsString());
		    }
	    }
    }


	public function getTopicsByQuestionId($questionId){

		$dbSelect = $this->select()->setIntegrityCheck(false);

		$select = $dbSelect ->from(array('a'=>$this->_name))
							->join(array('b'=>'topics'),'b.id = a.topic_id',array('topic_id' => 'id', 'topic_name' => 'topic', 'url_name'))
							->where('a.question_id = ?', intval($questionId));

		try{

			return $this->fetchAll($select);

		}catch (Exception $e){
			$log = new Zend_Log(
				new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
			);
			$log->debug($e->getMessage() . "\n" .
				$e->getTraceAsString(). "\n". $select);

			return false;
		}
	}

	public function updateQuestionTopics($questionId, $data){

		$select = $this->select()
						->where('question_id = ?', $questionId);

		$results = $this->fetchAll($select);
		$data = array_map('intval', $data);

		foreach ($results as $result) {
			if(($key = array_search($result['id'], $data)) !== false) {
				unset($data[$key]);
			}else{
				$result->delete();
			}
		}

		for($i = 0 ; $i < count($data); $i++){
			$row = $this->createRow();

			$row->question_id   =   $questionId;
			$row->topic_id      =   $data[$i];
			$row->created       =   date('Y-m-d H:i:s', time()+5.5*60*60);

			$row->save();
		}

	}

}