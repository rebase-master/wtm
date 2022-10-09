<?php
class Model_NotesCategory extends Zend_Db_Table_Abstract{
    
    protected $_name='notes_category';
	protected $_cols = array('id','category','type', 'created', 'visible');

    public function addCategory($category, $type){
        $row=$this->createRow();
        $row->category = strtolower($category);
        $row->type = $type;
	    $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
	    return $row->save();
    }

    public function readCategories($visible = null, $type = null){
        $select = $this->select();

	    if(!empty($visible))
          $select = $select->where('visible = ?', $visible);

	    if(!empty($type))
          $select = $select->where('type = ?', $type);

        return $this->fetchAll($select);
    }

    public function getCategoryId($category){
        $select=$this->select()
                ->from($this->_name, 'id')
                ->where('category=?',$category);
        $result=$this->fetchRow($select);
        return $result->id;
        //return $select;
    }
	public function updateNotesCategory($id, $category, $type){
		$row=$this->find($id)->current();
		if($row){
			$row->category = strtolower($category);
			$row->type = $type;
			$row->save();
			return true;
		}else{
			return false;
		}
	}
	public function removeNoteCategory($id)
	{
		$select=$this->select()
			->where('id=?',$id);
		$row=$this->fetchRow($select);
		if ($row) {
			$row->delete();
			return true;
		} else {
			return false;
		}
	}

	public function findById($id){
		$row=$this->find($id)->current();
		return $row;
	}

	public function findAllByCategory($category){

		$select = $this->select()->setIntegrityCheck(false)
			->from(array('a'=>$this->_name))
			->joinLeft(array('b'=>'notes'), 'a.id = b.category', array('sub_category', 'slug', 'deleted'))
			->where('a.category = ? ', $category)
			->where('b.deleted = 0');

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
			->from(array('a'=>$this->_name))
			->joinLeft(array('b'=>'notes_sub_category'), 'a.id = b.category', array('sub_category'))
			->joinLeft(array('c' => 'notes'), 'a.id = c.category', array('slug'))
			->where('a.type = ? ',$type);
//		echo $select;
//		die;
		return $this->fetchAll($select);
	}
}