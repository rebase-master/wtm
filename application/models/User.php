<?php
class Model_User extends Zend_Db_Table_Abstract{

    protected $_name='users';
	protected $_cols = array('id','email','admin','type','access_token','password',
	'registration_key','confirmed','username','first_name','last_name','gender','created',
	'updated','last_login');

    public function getEmails(){
        $select=$this->select()->from($this->_name, 'email')->where('confirmed=\'1\'');
        $result=$this->fetchAll($select);
        return $result;
    }
    public function generate_random_string($length=32){
        $seeds='abcdefghijklmnopqrstuvwxyz0123456789';
        $str="";
        $count=strlen($seeds);
        for($i=0;$i<$length;$i++){
            $str.=$seeds[mt_rand(0,$count-1)];
        }
        return $str;
    }
    public function createUser($data, $type=null){
       $row=$this->createRow();
       if($row){
        try{
            $registrationKey=$this->generate_random_string();
            $row->email=$data['email'];
           if($type == null){
                $row->gender=$data['gender'];
            }else{
                $row->type = $type;
                $row->gender=$data['gender']=='male'?'m':'f';
                $row['access_token'] = $data['access_token'];
           }
            $row->password          =   sha1($data['password']);
            $row->registration_key  =   $registrationKey;
            $row->confirmed         =   !empty($data['verified'])?1:0;
            $row->username          =   $data['username'];
            $row->first_name        =   $data['first_name'];
            $row->last_name         =   $data['last_name'];
            $row->created           =   date('Y-m-d H:i:s', time()+5.5*60*60);
            $row->updated           =   date('Y-m-d H:i:s', time()+5.5*60*60);
            $row->last_login        =   date('Y-m-d H:i:s', time()+5.5*60*60);
            $id = $row->save();
            if($type == null)
                return $registrationKey;
            else
                return $id;
        }catch(Exception $e){
            Phototour_Logger::log($e);
            return -1;
        }
       }else{
            return -1;
       }
    }
    public function updateAccessToken($access_Token, $email, $type){
        $dataRow=$this->getUserByEmail($email);
        if($dataRow){
            $dataRow->access_token = $access_Token;
            $dataRow->type = $type;
            return $dataRow->save();
        }else{
            return -1;
        }
    }
    public function confirmRegistration($registrationKey){
        $select=$this->select()
                ->where('registration_key=?',$registrationKey);
        $row=$this->fetchRow($select);
        if(count($row)==1){
            $row->confirmed=1;
            $row->save();
            return $row;
        }else{
            return false;
        }
    }
public function getRegistrationKey($email){
    $select=$this->select()
            ->from($this->_name, 'registration_key')
            ->where('email=?',$email);
    $result=$this->fetchRow($select);
    return count($result)>0?$result['registration_key']:null;
}
    public function getUsernameById($userId){
        $select=$this->select()
                ->from($this->_name, 'username')
                ->where('id=?',$userId)
                ->limit(1);
        $result=$this->fetchRow($select);
        return $result?$result->username:null;
    }

    public function getUserByEmail($email){
        $select=$this->select()
                ->where('email=?',$email);
        return $this->fetchRow($select);
    }

    public function fbUserExists($email){
        $select=$this->select()
                ->where('email=?',$email);
        return $this->fetchRow($select);
    }
    public function userExists($email){
        $select=$this->select()
                ->where('email=?',$email);
        return $this->fetchRow($select);
    }

    public function getUserByUsername($username){
        $select=$this->select()
                ->where('username=?',$username);
        return $this->fetchRow($select);
    }
    public function getUserById($userId){
        $select=$this->select()
                ->where('id=?',$userId)
                ->limit(1);
        return $this->fetchRow($select);
    }
    public function getUserCount(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num');
        $result=$this->fetchRow($select);
        return $result->num;
    }
    public function getMaleCount(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num')
                ->where('gender=\'m\'');
        $result=$this->fetchRow($select);
        return $result->num;
    }
    public function getFemaleCount(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num')
                ->where('gender=\'f\'');
        $result=$this->fetchRow($select);
        return $result->num;
    }
public function getUnverifiedUsers(){
        $select=$this->select()
                ->where('confirmed=\'0\'')
                ->order('created DESC');
        $result=$this->fetchAll($select);
        return $result;
    }
public function getWeeklyAbsentees(){
        $select=$this->select()
                //->from($this->_name, array()
                ->where('confirmed=1')
                ->where('DATEDIFF(NOW(),last_login)>7');
        $result=$this->fetchAll($select);
        return $result;
    
}
public function getUnverifiedUsersCount(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num')
                ->where('confirmed=0');
        $result=$this->fetchRow($select);
        return $result->num;
    }
    public function getThemAll(){
        $select=$this->select()
        ->order('last_login DESC');
        $result=$this->fetchAll($select);
        return count($result)>0?$result:null;
    }
    public function getUsersByTag($n=28, $uid = -1){
        $select = $this->select()->setIntegrityCheck(false)
         ->from(array('a'=>'users'), array('username', 'last_login', 'gender'))
         ->join(array('b'=>'user_data'), 'a.id=b.user_id',array('user_id','data'))
         ->join(array('c'=>'profile_options'), 'b.option_id=c.id AND c.options=\'display_picture\'')
         ->where('a.id != ?', $uid)
         ->order('created DESC')
         ->limit($n);
         $result=$this->fetchAll($select);
    return count($result)>0?$result:null;
    }
    public function getUsersByYear($year){
        $sql = "SELECT COUNT( u.id ) AS total, m.month
                FROM (
                SELECT 'Jan' AS
                MONTH
                UNION SELECT 'Feb' AS
                MONTH
                UNION SELECT 'Mar' AS
                MONTH
                UNION SELECT 'Apr' AS
                MONTH
                UNION SELECT 'May' AS
                MONTH
                UNION SELECT 'Jun' AS
                MONTH
                UNION SELECT 'Jul' AS
                MONTH
                UNION SELECT 'Aug' AS
                MONTH
                UNION SELECT 'Sep' AS
                MONTH
                UNION SELECT 'Oct' AS
                MONTH
                UNION SELECT 'Nov' AS
                MONTH
                UNION SELECT 'Dec' AS
                MONTH
                ) AS m
                LEFT JOIN users u ON MONTH( STR_TO_DATE( CONCAT( m.month, ' $year' ) , '%M %Y' ) ) = MONTH( u.created )
                AND YEAR( u.created ) = $year
                GROUP BY m.month
                ORDER BY 1 +1";
        $result = $this->getAdapter()->fetchAll($sql);
    return count($result)>0?$result:null;
    }
    public function getIdByEmail($email){
        $select=$this->select()
                ->from($this->_name, 'id')
                ->where('email=?',$email);
        $result=$this->fetchRow($select);
        return count($result)>0?$result->id:null;
    }
    public function getIdByUsername($username){
        $select=$this->select()
                ->from($this->_name, 'id')
                ->where('username=?',$username);
        $result=$this->fetchRow($select);
        return count($result)>0?$result->id:null;
    }
    public function findGenderById($id){
        $select=$this->select()
                ->from($this->_name, 'gender')
                ->where('id=?',$id);
        $result=$this->fetchRow($select);
        return count($result)>0?$result:null;
    }
    public function encodeUsername($username){
        $username=str_rot13($username);
        return $username;
    }
    public function decodeUsername($username){
        $temp="";
        for($i=0;$i<strlen($username);$i++){
            $temp.=chr(substr($username,$i,1))-13;
        }
        return $temp;
    }
    public function getEmailById($id){
        $select=$this->select()
                ->from($this->_name, array('email'))
                ->where('id=?',$id);
        $row=$this->fetchRow($select);
        if($row){
        return $row;
        }else{
            return null;
        }
    }

	public function blockUser($uid, $mode){

		$select = "UPDATE ".$this->_name." SET blocked = ".$mode." WHERE id = ".intval($uid)." LIMIT 1";
		try{
			return $this->getAdapter()->query($select);
		}catch (Exception $e){
			$log = new Zend_Log(
				new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
			);
			$log->debug($e->getMessage() . "\n" .
				$e->getTraceAsString());

			return false;
		}
	}

	public function verfiyUser($uid, $mode){

		$select = "UPDATE ".$this->_name." SET confirmed = ".$mode." WHERE id = ".intval($uid)." LIMIT 1";
		try{
			return $this->getAdapter()->query($select);
		}catch (Exception $e){
			$log = new Zend_Log(
				new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
			);
			$log->debug($e->getMessage() . "\n" .
				$e->getTraceAsString());

			return false;
		}
	}

}