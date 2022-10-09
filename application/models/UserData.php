<?php
class Model_UserData extends Zend_Db_Table_Abstract{
    //override default table name
    protected $_name='user_data';
	protected $_cols = array('sno','option_id','user_id','data','created');

    public function saveData($userId,$data){
       
        $mdlProfileOptions=new Model_ProfileOptions();
             
          foreach($data as $key =>$value){
            if($key!='id'){
                $optionId=$mdlProfileOptions->getIdByOptionName($key);
                if($optionId!=null && $value!=null){
                    $dataRow=$this->findRowByOptionUserId($optionId->id, $userId);
                    if($dataRow){
                        $dataRow->data=$value;
                        $dataRow->save();
                    }else{
                        $row=$this->createRow();
                        $row->option_id=$optionId->id;
                        $row->user_id=$userId;
                        $row->data=$value;
	                    $row->created = date('Y-m-d H:i:s', time()+5.5*60*60);
                        $row->save();
                    }
                }
            }
        }
    }
    private function findRowByOptionUserId($optionId,$userId){
        $select=$this->select()
                  ->where('option_id=?',$optionId)
                  ->where('user_id=?',$userId);
        $row=$this->fetchRow($select);
        return $row;
    }

    public function findUserById($userId){
        $select=$this->select('data')
                  ->where('user_id=?',$userId);
        $row=$this->fetchAll($select);
        return $row;
    }
    public function updatePhoto($userId, $optionId, $photoSource){
        $dataRow=$this->findRowByOptionUserId($optionId, $userId);
        if($dataRow){
            $dataRow->data=$photoSource;
            $dataRow->save();
        }else{
            $row=$this->createRow();
            $row->option_id=$optionId;
            $row->user_id=$userId;
            $row->data=$photoSource;
            $row->save();
        }
    }
    public function findPhotoByUsername($username){

      $select = $this->select()->setIntegrityCheck(false)
         ->from(array('a'=>'user_data'))
         ->join(array('b'=>'profile_options'), 'a.option_id=b.id AND b.options=\'display_picture\'')
         ->join(array('c'=>'users'),'c.id=a.user_id',null)
         ->where('c.username=?',$username);
      $result=$this->fetchRow($select);
      return count($result)>0?$result->data:null;
    }    
    public function findPhotoById($id){

      $select = $this->select()->setIntegrityCheck(false)
         ->from(array('a'=>'user_data'))
         ->join(array('b'=>'profile_options'), 'a.option_id=b.id AND b.options=\'display_picture\'')
         ->join(array('c'=>'users'),'c.id=a.user_id',null)
         //->where('user_id=?',$userId)
         ->where('c.id=?',$id);

      return $this->fetchRow($select);
    }    
    public function getSessionPhotoById($id, $gender){

      $select = "SELECT a.data as dp FROM user_data as a
                JOIN profile_options as b on a.option_id = b.id AND b.options = 'display_picture'
                JOIN users as c on c.id = a.user_id
                WHERE c.id = $id";
        $row = $this->getAdapter()->fetchRow($select);
        if(empty($row)){
            if($gender == 'f'){
                $src = '/images/ft.png';
            }else{
                $src = '/images/mt.png';
            }
            return $src;
        }else{
            if(stripos($row['dp'], 'http') !== false)
                return $row['dp'];
            else
                return str_replace("images", "thumbs", $row['dp']);
        }
    }
    public function findOrMakePhotoById($id){

      $select = "SELECT a.data as dp, c.gender FROM user_data as a
                JOIN profile_options as b on a.option_id = b.id AND b.options = 'display_picture'
                RIGHT JOIN users as c on c.id = a.user_id
                WHERE c.id = $id";
        $row = $this->getAdapter()->fetchRow($select);

        $gender = $row['gender'];
        $dp = $row['dp'];
        if(empty($dp)){

            if($gender == 'f'){
                $src = 'images/ft.png';
            }else{
                $src = 'images/mt.png';
            }
            return BASE_URL.$src;
        }else{
            if(stripos($row['dp'], 'http') !== false)
                return $row['dp'];
            else
                return BASE_URL.str_replace("images", "thumbs", $row['dp']);
        }
    }
    public function getNoOfDps(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num')
                ->where('option_id=11');
        $result=$this->fetchRow($select);
        return $result->num;
    }
}