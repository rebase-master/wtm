<?php
class Model_Topics extends Zend_Db_Table_Abstract{
    
    protected $_name ='topics';
    protected $_cols = array('id','topic','url_name','visible','created');

	public function createTopic($data){

		$row=$this->createRow();

		$row->topic     =   strip_tags($data['topic']);
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

	public function readTopics(){

		return $this->fetchAll($this->select()->order("created DESC"));

	}

	public function updateTopic($id,$data){

		$row=$this->find($id)->current();

		if($row){

			$row->topic      =   strip_tags($data['topic']);
			$row->url_name  =   strip_tags($data['url_name']);
			$row->created   =   date("Y-m-d H:i:s");
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
		}else{
			return false;
		}
	}

	public function removeTopic($id)
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
			throw new Zend_Exception("Topic not found!");
		}
	}
	public function count(){
		$select=$this->select()
			->from($this->_name, 'COUNT(*) AS num');
		$result=$this->fetchRow($select);
		return $result->num;
	}

}