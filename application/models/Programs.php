<?php
class Model_Programs extends Zend_Db_Table_Abstract{
    
    protected $_name='programs';
	protected $_cols = array('id','topic_id','heading','slug','question','solution','created', 'visible');

    public function createProgram($data){

	    $row = $this->createRow();

        $row->topic_id  =   $data['topic_id'];
        $row->heading   =   !empty($data['heading'])? $data['heading']: null;
        $row->slug      =   $data['slug'];
        $row->question  =   $data['question'];
        $row->solution  =   $data['solution'];
	    $row->created   = date('Y-m-d H:i:s', time()+5.5*60*60);
        $row->visible   =   $data['visible'];

	    try{
		    return $row->save();
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());


		    return false;
	    }
    }

    public function listPrograms($topic_id = 0){

        if($topic_id!=0){
	        $select = $this->select()->setIntegrityCheck(false)
	         ->from(array('a'=>$this->_name))
	         ->join(array('b'=>'topics'), 'a.topic_id=b.id', array('topic'))
	         ->where('topic_id=?',$topic_id)
	         ->order('created DESC');

        }else{

	        $select = $this->select()->setIntegrityCheck(false)
	         ->from(array('a'=>$this->_name))
	         ->join(array('b'=>'topics'), 'a.topic_id=b.id', array('topic'))
	         ->order('created DESC');
 
        }

	    return $this->fetchAll($select);
    }

    public function getPracticalQuestions(){

	    $select = $this->select()->setIntegrityCheck(false)
		         ->from(array('a'=>$this->_name))
		         ->join(array('b'=>'topics','topic'), 'a.topic_id=b.id')
		         ->where('topic in (2015, 2014,2013,2012,2011,2010,2009,2008,2007,2006,2005,2004,2003)')
		         ->where('a.visible = ', true)
 		         ->order('topic DESC');

	    return $this->fetchAll($select);
    }

    public function deleteProgram($id)
    {
        $row = $this->find($id)->current();
        if ($row) {
            $row->delete();
            return true;
        } else {
            throw new Zend_Exception("Delete function failed; could not find page!");
        }
    }

    public function updateProgram($id, $data){

	    try{

	        $row = $this->find($id)->current();

		    if($row){

			    $row->topic_id  =   $data['topic_id'];
			    $row->heading   =   !empty($data['heading'])? $data['heading']: null;
			    $row->slug      =   $data['slug'];
			    $row->question  =   $data['question'];
			    $row->solution  =   $data['solution'];
			    $row->visible   =   $data['visible'];

			    return $row->save();
		    }
        }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->log("ERROR: ", $e->getTraceAsString());

		    return false;
	    }
    }

    public function findById($id){

        $row = $this->find($id)->current();

        try{

            return $row;

        }catch(Exception $e) {
	        $log = new Zend_Log(
		        new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
	        );
	        $log->log("ERROR: Program not found", null);

	        return false;
        }
    }

	public function count(){
		$select=$this->select()
			->from($this->_name, 'COUNT(*) AS num');
		$result=$this->fetchRow($select);
		return $result->num;
	}

	public function findBySlug($slug){

//		$select = $this->select()->setIntegrityCheck(false)
//						->from(array('a' => $this->_name))
//						->join(array('b' => 'topics'), 'a.topic_id = b.id', array('topic', 'url_name'))
//						->where('slug = ?', $slug);

		$select = $this->select()
						->where('slug = ?', $slug)
						->where('visible = true');

		return $this->fetchRow($select);
	}

	public function relatedPrograms($topicId){

		$select = $this->select()
					   ->where('topic_id = ?', $topicId)
						->where('visible = true')
					   ->limit(5);

		return $this->fetchAll($select);
	}

	public function getByTopic($topics, $limit = null){

		$dbselect = $this->select()->setIntegrityCheck(false);
		$select = $dbselect ->from(array('a'=>$this->_name))
							->join(array('b'=>'topics'), 'a.topic_id=b.id', array('topic'))
							->where('b.url_name in (?)', $topics)
							->where('a.visible = true')
							->where('b.visible = true');

		if($limit != null)
			$select = $dbselect->limit($limit);

		try{
			return $this->fetchAll($select);
		}catch (Exception $e){
			$log = new Zend_Log(
				new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
			);
			$log->log("ERROR: ", $e->getTraceAsString());

			return null;
		}
	}
}