<?php
class Model_Subjects extends Zend_Db_Table_Abstract{
    //override default table name
    protected $_name = 'subjects';
    protected $_cols = array('id','name','url_name', 'visible', 'created');

	public function createSubject($data){

		$row=$this->createRow();

		$row->name      =   strip_tags($data['name']);
		$row->url_name  =   strip_tags($data['url_name']);
		$row->created   = date('Y-m-d H:i:s', time()+5.5*60*60);
		$row->visible   =   strip_tags($data['visible']) == true? 1: 0;

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

	public function readSubjects(){

		$result = $this->fetchAll($this->select()->order("created DESC"));

		return $result;
	}

	public function updateSubject($id,$data){

		$row=$this->find($id)->current();

		if($row){

			$row->name      =   strip_tags($data['name']);
			$row->url_name  =   strip_tags($data['url_name']);
			$row->created   =   date("Y-m-d H:i:s");
			$row->visible   =   strip_tags($data['visible']) == true? 1: 0;

//			var_dump($row);
//			die;
			try{
				return $row->save();
			}catch (Exception $e){
				$log = new Zend_Log(
					new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
				);
				$log->log("ERROR: ", $e->getTraceAsString());
				return false;
			}
		}else{
			return false;
		}
	}

	public function removeSubject($id)
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
		$row=$this->find($id)->current();
		if($row){
			return $row;
		}else{
			throw new Zend_Exception("Subject not found!");
		}
	}

	public function findIdByName($subjectName){

        $select=$this->select()
                ->where('subject=?',$subjectName)
                ->limit(1);
        return $this->fetchRow($select);

    }

	public function count(){
		$select = $this->select()
			->from($this->_name, 'COUNT(*) AS num');
		$result = $this->fetchRow($select);
		return $result->num;
	}

}