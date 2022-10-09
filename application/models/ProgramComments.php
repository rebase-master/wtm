<?php
class Model_ProgramComments extends Zend_Db_Table_Abstract{
    
    protected $_name='program_comments';
	protected $_cols = array('id','pid','uid','comment','deleted','flagged','created');

    public function createComment($data){

	    $row = $this->createRow();

        $row->pid       =   intval($data['pid']);
        $row->uid       =   intval($data['uid']);
	    $row->comment   =   $data['comment'];
	    $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);

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

	//Return all comments made on a program identified by $pid
    public function getComments($pid, $offset = 0, $limit = 15){

	    $select = $this->select()
		               ->setIntegrityCheck(false)
				       ->from(array('pc'=>$this->_name))
				       ->join(array('u'=>'users'), 'pc.uid = u.id', array('username'))
				       ->where('pc.pid = ?', $pid)
	                   ->where('pc.deleted = false')
	                   ->limit($limit, $offset)
	                   ->order("created DESC");

	    return $this->fetchAll($select);
    }

	//TODO: Return all comments made by the user identified by $uid
    public function listUserComments($uid){

    }

    public function deleteComment($id)
    {
        $row = $this->find($id)->current();
        if ($row) {
            $row->delete();
            return true;
        } else {
            throw new Zend_Exception("Delete function failed; could not find comment!");
        }
    }

}