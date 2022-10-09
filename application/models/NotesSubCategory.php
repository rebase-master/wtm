<?php
class Model_NotesSubCategory extends Zend_Db_Table_Abstract{
    
    protected $_name='notes_sub_category';
    protected $_columns = array('id','category','sub_category','created','visible');

    public function addSubCategory($category, $subcategory){
        $row=$this->createRow();
	    $row->category = intval($category);
        $row->sub_category = ucwords($subcategory);
	    $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
	    return $row->save();
    }
    public function readSubCategories(){
		$select = $this->select()->setIntegrityCheck(false)
		->from(array('a'=>'notes_category'))
		->join(array('b'=>'notes_sub_category'), 'a.id = b.category')
		->order('b.created DESC');

		return $this->fetchAll($select);
    }

	public function updateNotesSubCategory($id, $category, $subcategory){
		$row=$this->find($id)->current();
		if($row){
			$row->category = $category;
			$row->sub_category = ucwords($subcategory);
			$row->save();
			return true;
		}else{
			return false;
		}
	}

	public function removeNotesSubCategory($id)
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


}