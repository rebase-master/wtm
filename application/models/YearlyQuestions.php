<?php
class Model_YearlyQuestions extends Zend_Db_Table_Abstract{
    
    protected $_name = 'yearly_questions';
    protected $_cols = array('id','year','subject','type','position','slug','heading','question','solution','created', 'visible');

    public function createQuestion($data){

	    $row=$this->createRow();
        $row->year      =   intval($data['year']);
        $row->subject   =   strip_tags($data['subject']);
        $row->type      =   strip_tags($data['type']);
	    $row->position  =   intval($data['position']);
	    $row->slug      =   $this->slugify(substr(strip_tags($data['question']), 0, 70));
	    $row->question  =   $data['question'];
	    $row->heading   =   isset($data['heading'])?$data['heading']:null;
        $row->solution  =   $data['solution'];
        $row->created   =   date("Y-m-d H:i:s");
        $row->visible   =   intval($data['visible']) == 1? 1:0;
//	    die;
	    try{
		    return $row->save();
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );

		    $log->log("ERROR: ", $e->getTraceAsString());

		    return false;
	    }
    }
	private function slugify($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		$existingSlugCount = count($this->findBySlug($text.'%'));

		if($existingSlugCount  > 0 ){
			$text.= "-".($existingSlugCount+1);
		}

//		echo "slug:".$text;
		return $text;
	}

	public function updateQuestion($id,$data){
//		var_dump($data);
//		die;

		$row = $this->find($id)->current();

		if(empty($row)){
			throw new Zend_Acl_Exception;
		}else{
			try{
				$row->year      =   intval($data['year']);
				$row->subject   =   strip_tags($data['subject']);
				$row->type      =   strip_tags($data['type']);
				$row->position  =   intval($data['position']);
				$row->heading   =   $data['heading'];

				if(empty($row->slug))
					$row->slug      =   $this->slugify(substr($data['question'], 0, 70));
				else
					$row->slug      =   $data['slug'];

				$row->question  =   $data['question'];
				$row->solution  =   $data['solution'];
				$row->visible   =   strip_tags($data['visible']) == true? 1:0;

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
	}

	public function findBySlug($slug){
		$select=$this->select()
			->where('slug LIKE ?',$slug);
		return $this->fetchAll($select);
	}

	public function findOneBySlug($subject, $type, $slug){
		$select = $this->select()->setIntegrityCheck(false)
						->from(array('a'=>$this->_name))
						->join(array('b'=>'subjects'),'b.id = a.subject',array('subject' => 'name', 'url_name'))
						->where('a.visible=?',true)
						->where('b.url_name = ?',$subject)
						->where('a.type = ?', $type)
						->where('a.slug = ?', $slug);
		return $this->fetchRow($select);
	}

	//This function was created to add slug to existing question
	//This only needs to be executed after adding the column 'slug'
	//if the db is not empty
	public function addSlugToQuestion(){
		try{

			$rows = $this->readAllQuestions();

			foreach ($rows as $row) {
				if(empty($row->slug)){
					$row->slug      =   $this->slugify(substr(strip_tags($row->question), 0, 70));
					$row->save();
				}
			}

		}catch (Exception $e){
			$log = new Zend_Log(
				new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
			);
			$log->debug($e->getMessage() . "\n" .
				$e->getTraceAsString());

			return false;
		}

	}

    public function readAllQuestions(){

	    $dbSelect = $this->select()->setIntegrityCheck(false);

		$select   = $dbSelect ->from(array('a'=>$this->_name))
							->join(array('b'=>'subjects'),'b.id = a.subject',array('subject' => 'name'))
							->where('a.visible=?',true)
	                        ->order('a.created DESC');

	    try{
		    return $this->fetchAll($select);
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

		    return false;
	    }
    }

	//Returns a single record comprising question, solution of the given subject/year/type
    public function getYearlyQuestion($type = 'practical', $year = null, $subject = 'computer science', $questionNumber = null){

	    if(strpos($subject, '-'))
		    $subject = str_replace('-',' ', $subject);

	    $dbSelect = $this->select()->setIntegrityCheck(false);

		$select = $dbSelect->from(array('a'=>$this->_name), array('id','year','type','position','slug','question','heading', 'solution','visible'))
							->join(array('b'=>'subjects'),'b.id = a.subject',array('subject' => 'name', 'url_name'))
							->where('b.name = ?',$subject)
					        ->where('a.type=?',$type);

        if(!empty($year))
	        $select = $dbSelect->where('a.year=?',$year);

        if(!empty($questionNumber))
	        $select = $dbSelect->where('position = ?', $questionNumber);

		$select = $dbSelect->where('a.visible=?',true);

	    try{
		    if(empty($year) && empty($questionNumber))
			    return $this->fetchAll($select);
		    else
			    return $this->fetchRow($select);
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

		    return false;
	    }
    }

	//Returns records comprising question, solution of the given subject/year/type
    public function getYearlyQuestions($type = null, $year = null, $subject = 'computer science', $limit = null){

	    $dbSelect = $this->select()->setIntegrityCheck(false);

		$select = $dbSelect->from(array('a'=>$this->_name), array('id','year','type','position','question','heading','slug','solution','visible'))
							->join(array('b'=>'subjects'),'b.id = a.subject',array('subject' => 'name', 'url_name'))
							->where('b.name=?',$subject)
					        ->where('a.slug != \'n-a\'')
						    ->where('a.visible=?',true);

        if(!empty($type)){
	        $select = $dbSelect->where('a.type=?',$type);

	        if($type == 'practical')
		        $select = $dbSelect->order('a.position ASC');
        }

        if(!empty($year))
	        $select = $dbSelect->where('a.year=?',$year);

        if(!empty($limit))
	        $select = $dbSelect->limit($limit);

//	    echo $select;
//	    die;
	    try{
		    return $this->fetchAll($select);
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

		    return false;
	    }
    }

    public function getPracticalQuestions($subject = 'computer science', $type = 'practical', $year = null){

	    $dbSelect = $this->select()->setIntegrityCheck(false);

		$select = $dbSelect ->from(array('a'=>$this->_name), array('id','year','type','position','visible'))
							->join(array('b'=>'subjects'),'b.id = a.subject',array('subject' => 'name'))
	                        ->order('a.created DESC');

        if(!empty($year))
           $select = $dbSelect->where('b.name=?',$subject)
	                          ->where('a.type=?',$type)
	                          ->where('a.year=?',$year)
	                          ->where('a.visible=?',true);
        else
	       $select = $dbSelect->where('a.type=?',$type)
				              ->where('b.name=?',$subject)
				              ->where('a.visible=?',true);

//	    echo $select;
	    try{
		    return $this->fetchAll($select);
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

		    return false;
	    }
    }

    public function getByTags($tags, $limit = 5){

	    $topics = array();
	    foreach ($tags as $tag) {
		    array_push($topics, $tag['topic_id']);
	    }
	    $topics = array_map('intval', $topics);

	    $dbSelect = $this->select()->setIntegrityCheck(false);

		$select = $dbSelect ->from(array('yq'  => $this->_name), array('id', 'year', 'position','question'))
							->join(array('yqt' => 'yearly_question_topics'), 'yq.id = yqt.question_id')
							->join(array('t'   => 'topics'), 't.id = yqt.topic_id')
							->where("yqt.topic_id IN (".implode(",", $topics).")")
							->group('yq.id')
	                        ->order('yq.year DESC')
							->limit($limit);

//	    $log = new Zend_Log(
//		    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
//	    );
//	    $log->debug($select."\n");
	    try{
		    return $this->fetchAll($select);
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

		    return false;
	    }
    }

	public function removeQuestion($id)
    {
        $row = $this->find($id)->current();

	    try{
		    return $row->delete();
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->log("ERROR: ", $e->getTraceAsString());
		    return false;
	    }
    }

    public function findById($id){
	    try{
		    return $this->find($id)->current();
	    }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->log("ERROR: ", $e->getTraceAsString());
		    return false;
	    }
    }
}