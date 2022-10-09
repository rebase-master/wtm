<?php
class Model_Riddles extends Zend_Db_Table_Abstract{
    
    protected $_name = 'riddles';
    protected $_cols = array('id','riddle','answer', 'visible', 'created');

    public function save($riddle, $answer){
    $row=$this->createRow();
    if($row){
	    $row->riddle    = $riddle;
	    $row->answer    = $answer;
	    $row->created   = date('Y-m-d H:i:s', time()+5.5*60*60);
	    return $row->save();
    }else{
        throw new Exception();
    }
    }
    public function get(){
        $select=$this->select()
               ->order(new Zend_Db_Expr('RAND()'));
        return $this->fetchAll($select);
    }
	public function removeRiddle($id)
	{
		$row = $this->find($id)->current();
		return $row->delete();
	}
	public function updateRiddle($id, $riddle, $answer){
		$row=$this->find($id)->current();
		if($row){
			$row->riddle = $riddle;
			$row->answer = $answer;
			$row->save();
			return true;
		}else{
			return false;
		}

	}

	public function findById($id){
		return $this->find($id)->current();
	}

	public function listRiddles(){
		$select=$this->select()
			->order('created DESC');

		return $this->fetchAll($select);
	}

	public function count(){
		$select=$this->select()
			->from($this->_name, 'COUNT(*) AS num');
		$result=$this->fetchRow($select);
		return $result->num;
	}

}