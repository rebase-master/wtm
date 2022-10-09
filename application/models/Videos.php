<?php
class Model_Videos extends Zend_Db_Table_Abstract{
    //override default table name
    protected $_name='videos';
	protected $_cols = array('id','heading','link','description','source','created');

	public function addVideo($data){
        $row=$this->createRow();
        if($row){
            $row->heading=$data['heading'];
            $row->link=$data['link'];
            $row->source=$data['source'];
            $row->description=$data['description'];
            $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
            return $row->save();
        }else{
            throw new Zend_Exception('Error adding video.');
        }
    }
    public function countVideos(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num');
        $result=$this->fetchRow($select);
        return $result->num;
    }
    public function randomVideo(){
        $length=$this->countVideos();
        $select=$this->select()
                ->where('id=?', mt_rand(1,$length));
    $result=$this->fetchRow($select);

    if($result){
        return $result;
    }else{
        return $this->randomVideo();
        }
    }
    public function findById($id){
    $select=$this->select()
            ->where('id=?',$id);
    $result=$this->fetchRow($select);
    return count($result)>0?$result:null;
    }
  public function updateVideo($id,$data){
	$data=array("heading"=>$data['heading'], "link"=>$data['link'], "description"=>$data['description'], "source"=>$data['source']);
	//$where="id=?".$entryId." uid=?".$uid." diary_id=?".$diaryId;
	$where=$this->getAdapter()->quoteInto("id=?",$id);
	
	$result=$this->update($data, $where);
	return count($result)>0?true:false;	
  }
  public function deleteVideo($id){
	$where=$this->getAdapter()->quoteInto('id=?',$id);
	return $this->delete($where);
	//return $where;
  }
    public function allVideos(){
        $select = $this->select()
	        ->order("created DESC");
        $result = $this->fetchAll($select);
        return count($result)>0?$result:null;
    }
}