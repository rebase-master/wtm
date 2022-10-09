<?php
class Model_Tags extends Zend_Db_Table_Abstract{
    
    protected $_name = 'tags';
    protected $_cols = array('id','tag','created');
    
    public function addQuestion($uid, $tag){
        $row=$this->createRow();
        if($row){
            $row->uid = $uid;
            $row->topic = $tag;
            $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);

            return $row->save();
        }else{
            return false;
        }
    }
    public function findById($tag){
        $select= $this->select()
                    ->where('tag = ?',$tag);
        //return $select;
        try{
            $result=$this->fetchRow($select);
        }catch(Exception $e){
            Phototour_Logger::log($e);
            $result = false;
        }
        return count($result)>0?$result:null;
    }
    public function findAll(){
        $select = 'SELECT tag from tags';
        return $this->getAdapter()->fetchAll($select);
    }
}