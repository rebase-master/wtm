<?php
class Model_Notes extends Zend_Db_Table_Abstract{
    
    protected $_name='notes';
	protected $_cols = array('id', 'category', 'sub_category', 'slug', 'created','deleted');

    public function add($data){

        $row = $this->createRow();

        $row->category      =   intval($data['category']);
        $row->sub_category  =   $data['sub_category'];
        $row->slug          =   strip_tags($data['slug']);
	    $row->created       =   date('Y-m-d H:i:s', time()+5.5*60*60);

	    return $row->save();
    }

    public function listNotes($category = null){

        if(!empty($category)){

	        $select = $this->select()->setIntegrityCheck(false)
		        ->from(array('a'=>$this->_name))
		        ->joinLeft(array('b'=>'notes_category'), 'a.category = b.id', array('category'))
		        ->where('b.category = ?', $category)
		        ->order('a.created DESC');
        }else{

	        $select = $this->select()->setIntegrityCheck(false)
		         ->from(array('a'=>$this->_name))
		         ->joinLeft(array('b'=>'notes_category'), 'a.category = b.id', array('category'))
		         ->order('a.created DESC');

        }

        $result = $this->fetchAll($select);

        return count($result)>0?$result:false;
    }

    public function listNotesSubCategories(){

        $select = $this->select()->setIntegrityCheck(false)
			         ->from(array('a'=>$this->_name))
			         ->joinLeft(array('b'=>'notes_category'), 'a.category = b.id', array('category'))
			         ->where('b.sub_category != ""')
			         ->order('a.created DESC');

        $result = $this->fetchAll($select);

        return count($result)>0?$result:false;
    }

    public function removeNote($id)
    {
        $select = $this->select()
                ->where('id=?',$id);
	    $note = $this->fetchRow($select);

        if ($note) {

	        $note->delete();
            return true;

        } else {
            return false;
        }
    }

    public function updateNotes($id,$data){

        $select = $this->select()
                ->where('id=?',$id);

        $row = $this->fetchRow($select);

        if($row){

            $row->category      =   intval($data['category']);
            $row->sub_category  =   $data['sub_category'];
            $row->slug          =   $data['slug'];
	        $row->deleted       =   $data['deleted'];
            $row->save();
        }
    }

    public function findById($id){
	    $select = $this->select()->setIntegrityCheck(false)
		    ->from(array('a'=>$this->_name))
		    ->joinLeft(array('b'=>'notes_content'), 'a.id = b.notes_id', array('ncid' => 'id', 'content', 'extract', 'source'))
		    ->where('a.id = ?', $id)
		    ->where('a.deleted = false')
		    ->where('b.deleted = false');

//	    echo $select;
//	    die;
        $row = $this->fetchRow($select);
        if($row){
            return $row;
        }else{
            return null;
        }
    }

    public function findBySlug($slug, $limit = null){
	    $dbselect = $this->select()->setIntegrityCheck(false);
	    $select = $dbselect ->from(array('a'=>$this->_name))
				    ->joinLeft(array('b'=>'notes_content'), 'a.id = b.notes_id', array('ncid' => 'id', 'heading','content', 'extract', 'source'))
		            ->where('a.slug = ?', $slug)
		            ->where('a.deleted = false')
		            ->where('b.deleted = false');

	    if(!empty($limit) && $limit == 1){
		    $dbselect = $select->limit($limit);
		    $row = $this->fetchRow($dbselect);
	    }else{
		    $row = $this->fetchAll($dbselect);
	    }

//	    echo $select;
//	    die;
        if($row){
            return $row;
        }else{
            return null;
        }
    }

	public function getSubjectCategories($type = 'subject'){
		$select = $this->select()->setIntegrityCheck(false)
			->from(array('a'=>$this->_name))
			->joinLeft(array('b'=>'notes_category'), 'a.category = b.id', array('category', 'type'))
			->where('b.type = ? ',$type);
		return $this->fetchAll($select);
	}

	public function getSubCategories(){
		$select = $this->select()->setIntegrityCheck(false)
			->from(array('a'=>$this->_name));

		return $this->fetchAll($select);
	}

    public function find($category = null){
	    $select = $this->select()->setIntegrityCheck(false)
			    ->from(array('a'=>$this->_name), array())
			    ->joinLeft(array('b'=>'notes_category'),'b.id = a.category',null)
			    ->joinLeft(array('c'=>'notes_content'), 'a.id = c.notes_id')
			    ->where('a.deleted = false')
			    ->where('c.deleted = false')
                ->where('b.category = ?',$category);

        $result = $this->fetchAll($select);
        return $result;
    }

    public function getRandomNote($category = 'computer'){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a'=>$this->_name))
		        ->joinLeft(array('b'=>'notes_category'),'b.id = a.category', array('category_name' => 'category'))
		        ->joinLeft(array('c'=>'notes_content'), 'a.id = c.notes_id', array('content', 'extract', 'source'))
		        ->where('a.deleted = false')
		        ->where('c.deleted = false')
                ->where('b.category=?', strtolower($category))
                ->where('c.extract != ""')
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

}