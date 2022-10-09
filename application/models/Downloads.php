<?php
class Model_Downloads extends Zend_Db_Table_Abstract{
    
    protected $_name='downloads';
	protected $_cols = array('id','resource','uid','created');

    public function add($uid, $resource){
       try{

        $row = $this->createRow();

        $row->resource = $resource;
        $row->uid = $uid;
        $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
       }catch(Exception $e){
           Phototour_Logger::log('Error adding downloads: '.$e);
       }
        return $row->save();
    }
    public function totalDownloads(){
        $sql = "SELECT COUNT(*) as downloads";
        return $this->getAdapter()->fetchRow($sql);
    }
    public function groupDownloads(){
        $sql = "SELECT resource, count(*) as total FROM downloads group by resource order by total DESC";
        return $this->getAdapter()->fetchAll($sql);
    }
}