<?php
class Model_NotesContent extends Zend_Db_Table_Abstract{
    
    protected $_name = 'notes_content';
	protected $_cols = array('id', 'notes_id', 'heading', 'slug', 'cover_image', 'content', 'extract', 'source','deleted','created','updated');

    public function add($data){

//	    var_dump($data);
//	    die;
        $row = $this->createRow();

        $row->notes_id      =   intval($data['notes_id']);
        $row->heading       =   strip_tags($data['heading']);
        $row->slug          =   strip_tags($data['slug']);
	    $row->cover_image   =   strip_tags($data['cover_image']);
	    $row->content       =   $data['content'];
        $row->extract       =   strip_tags($data['extract']);
        $row->source        =   urlencode($data['source']);
	    $row->created       =   date('Y-m-d H:i:s', time()+5.5*60*60);

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

	public function updateNotes($id, $data){

		$select = $this->select()
			->where('id = ?', $id);
//	    echo $select;
		$row = $this->fetchRow($select);
//	    var_dump($row);
//	    var_dump($data);
//	    die;
		if($row){
			$row->notes_id      =   intval($data['notes_id']);
			$row->heading       =   strip_tags($data['heading']);
			$row->slug          =   strip_tags($data['slug']);
			if(!empty($data['cover_image']))
				$row->cover_image   =   strip_tags($data['cover_image']);
			$row->content       =   $data['content'];
			$row->extract       =   strip_tags($data['extract']);
			$row->source        =   urlencode($data['source']);
			$row->updated       =   date('Y-m-d H:i:s', time()+5.5*60*60);
			var_dump($row);
			return $row->save();
		}
//		var_dump($row);

	}

	public function listNotes($subCategory = null, $type = null, $limit = null){

		$dbselect = $this->select()->setIntegrityCheck(false);

		if(!empty($subCategory)){

	        $select = $dbselect
			         ->from(array('a'=>$this->_name))
			         ->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', array('sub_category', 'notes_id' => 'id','notes_slug' => 'slug'))
			         ->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category'))
		             ->where('b.sub_category = ?', $subCategory)
	                 ->order('a.updated DESC');
		}else{
			$select = $dbselect
				->from(array('a'=>$this->_name))
				->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', array('sub_category','notes_id' => 'id','notes_slug' => 'slug'))
				->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category'))
				->order('a.updated DESC');

		}
		if(!empty($type)){
			$select = $dbselect
						->where('c.type = ?', $type);
		}
		if(!empty($limit))
			$select = $dbselect->limit($limit);

        $result = $this->fetchAll($select);

        return count($result)>0?$result:false;
    }

	public function homeStream($limit = null){

		$dbselect = $this->select()->setIntegrityCheck(false);

		$select = $dbselect
				->from(array('a'=>$this->_name))
				->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', array('sub_category', 'notes_id' => 'id','notes_slug' => 'slug'))
				->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category'))
				->order('a.created DESC');

		if(!empty($limit))
			$select = $dbselect->limit($limit);

        $result = $this->fetchAll($select);

        return count($result)>0?$result:false;
    }

    public function removeNote($id)
    {
        $select = $this->select()
                ->where('id=?',$id);
        $row = $this->fetchRow($select);

	    if ($row) {
	        if (!empty($row->cover_image) && file_exists(APPLICATION_PATH . '/../public/images/content/uploads/' . $row->cover_image)) {
		        unlink(APPLICATION_PATH . '/../public/images/content/uploads/' . $row->cover_image);
	        }

	        return $row->delete();

        } else {
            return false;
        }
    }

	public function findById($id){
	    $select = $this->select()->setIntegrityCheck(false)
			    ->from(array('a'=>$this->_name))
			    ->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', array('notes_id' => 'id', 'sub_category'))
			    ->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category' => 'id'))
			    ->where('a.id = ?', $id);

	    try{
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

    public function findBySlug($slug){
        $select = $this->select()
                ->where('slug=?',$slug);
        $row = $this->fetchRow($select);
//	    echo $select;
//	    die;
        if($row){
            return $row;
        }else{
            return null;
        }
    }

    public function findAllBySlug($slug){
        $select = $this->select()
                ->where('slug=?',$slug);
        $row = $this->fetchAll($select);
//	    echo $select;
//	    die;
        if($row){
            return $row;
        }else{
            return null;
        }
    }

    public function findAllByNotesSlug($slug){
	    $select = $this->select()->setIntegrityCheck(false)
			    ->from(array('a'=>$this->_name))
			    ->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', null)
			    ->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category', 'type'))
			    ->where('b.slug=?',$slug);
        $row = $this->fetchAll($select);
//	    echo $select;
//	    die;
        if($row){
            return $row;
        }else{
            return null;
        }
    }

    public function findAllByCategorySlug($category, $slug){

	    $select = $this->select()->setIntegrityCheck(false)
		    ->from(array('a'=>$this->_name))
		    ->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', array('sub_category'))
		    ->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category', 'type'))
		    ->where('c.category = ? ', str_replace("-", " ", $category))
		    ->where('a.slug = ? ', $slug);

//echo $select;
//die;
	    $row = $this->fetchRow($select);

        if($row){
            return $row;
        }else{
            return null;
        }
    }

    public function findRelatedNotes($notesId, $excludeId, $limit = 10){

	    $select = $this->select()->setIntegrityCheck(false)
				    ->from(array('a'=>$this->_name))
				    ->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', null)
				    ->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category'))
				    ->where('b.id = ? ', $notesId)
				    ->where('a.id != ? ', $excludeId)
		            ->limit($limit)
	                ;

//echo $select;
//die;
	    $row = $this->fetchAll($select);

        if($row){
            return $row;
        }else{
            return null;
        }
    }

    public function findAllByCategory($category){

	    $select = $this->select()->setIntegrityCheck(false)
		    ->from(array('a'=>$this->_name))
		    ->joinLeft(array('b'=>'notes'), 'a.notes_id = b.id', array('sub_category'))
		    ->joinLeft(array('c'=>'notes_category'), 'b.category = c.id', array('category', 'type'))
		    ->where('c.category = ? ', $category)
	        ->order('a.created DESC');

//echo $select;
//die;
	    $row = $this->fetchAll($select);

        if($row){
            return $row;
        }else{
            return null;
        }
    }

	public function getSubjectCategories($type = 'subject'){
		$select = $this->select()->setIntegrityCheck(false)
			->from(array('a'=>$this->_name), array('heading','slug'))
			->joinLeft(array('b'=>'notes_category'), 'a.category = b.id', array('category', 'type'))
			->where('b.type = ? ',$type);
//		echo $select;
//		die;
		return $this->fetchAll($select);
	}

    public function find($category = null){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name))
                ->join(array('b'=>'notes_category'),'b.id = a.category',null)
                ->where('b.category=?',$category)
                ->order(new Zend_Db_Expr('RAND()'));
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getRandomNote($category = 'computer'){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name))
                ->join(array('b'=>'notes_category'),'b.id=a.category',null)
                ->where('b.category=?',$category)
                ->order(new Zend_Db_Expr('RAND()'));
        $result = $this->fetchRow($select);
        return $result;
    }

    public function randomPerson($category){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name))
                ->join(array('b'=>'notes_category'),'b.id = a.category',null)
                ->where('b.category=?',$category)
                ->order(new Zend_Db_Expr('RAND()'))
                ->limit(1);
        $result = $this->fetchRow($select);
        return $result;
    }

	public function count(){
		$select = $this->select()
			->from($this->_name, 'COUNT(*) AS num');
		$result = $this->fetchRow($select);
		return $result->num;
	}





	/**
	 * This function needs to be run once when the new table notes_content is created
	 * It'll fetch all the records from the table <notes> and
	 * move the content, extract, source and image to the new table
	 * After that these columns will be deleted from the table <notes>
	 */
	public function migrateNotes($notes){

		foreach ($notes as $note) {

			$row = $this->createRow();

			$row->notes_id      =   $note->id;
			$row->content       =   $note->content;
			$row->extract       =   strip_tags($note->extract);
			$row->source        =   urlencode($note->source);
			$row->created       =   date('Y-m-d H:i:s', time()+5.5*60*60);

			try{
//				$row->save();    --> Uncomment this line to save records
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

}