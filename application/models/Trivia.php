<?php
class Model_Trivia extends Zend_Db_Table_Abstract{
    
    protected $_name='trivia';
	protected $_cols = array('id','fact','created');

    public function createTrivia($fact){
        $row=$this->createRow();
        if($row){
            $row->fact=$fact;
	        $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
	        return $row->save();
        }else{
            throw new Zend_Exception;
        }
    }

	public function readTrivia(){
		$select=$this->select()
			->order('created DESC');
		$result=$this->fetchAll($select);
		return count($result)>0?$result:false;
	}

	public function updateTrivia($id, $fact){
		$row=$this->find($id)->current();
		if($row){
			$row->fact=$fact;
			$row->save();
			return true;
		}else{
			return false;
		}

	}

	public function deleteTrivia($id)
	{
		// find the row that matches the id
		$row = $this->find($id)->current();
		if ($row) {
			$row->delete();
			return true;
		} else {
//            throw new Zend_Exception("Delete function failed; could not find page!");
			return false;
		}
	}

	public function randomTrivia(){
        $select=$this->select()
                ->order(new Zend_Db_Expr('RAND()'))
                ->limit(1);
    $result=$this->fetchRow($select);

    if($result){
        return $result;
    }else{
        return false;
        }
    }
    public function listRandomTrivia(){
       $select=$this->select()
                ->order(new Zend_Db_Expr('RAND()'));
       $result=$this->fetchAll($select);
       return count($result)>0?$result:false;
    }
	public function findById($id){
		$row = $this->find($id)->current();
		return $row;
	}

	public function countTrivia(){
        $select=$this->select()
            ->from($this->_name, 'COUNT(*) AS num');
        $result=$this->fetchRow($select);
        return $result->num;
    }

}