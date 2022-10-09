<?php
$mdlUser=new Model_User();
$totalUsers=$mdlUser->getUserCount();
$friends=array();
$param=Zend_Controller_Front::getInstance()->getRequest()->getParam('user');
$totalFriends=0;
if (Zend_Auth::getInstance()->hasIdentity()) {
    try{

    $mdlFriends=new Model_Friends();
    $mdlUserData=new Model_UserData();
    $mdlInvitations = new Model_Invitations();

    $identity = Zend_Auth::getInstance()->getIdentity();
    $loggedinusername =$identity->username;
    $loggedinuserid = $identity->id;

    $currentUser=$mdlUser->getUserByEmail($identity->email);
    $friendRequests = $mdlInvitations->getInvitationsById($currentUser->id);
    $nopf = count($friendRequests);

    if(isset($param) && $param!=$identity->username){
        $userP=$mdlUser->getUserByUsername($param);
    }
    else{
        $userP=$mdlUser->getUserByUsername($loggedinusername);
    }
    if(!empty($userP)){

        $userId=$userP->id;
        $results=$mdlFriends->getFriends($userP->username);
        $totalFriends=$mdlFriends->getFriendCount($userP->username);
        $i=0;
        foreach($results as $result){
            if($result->my_id==$userP->id)
                $find=$result->friends_id;
            if($result->friends_id==$userP->id)
                $find=$result->my_id;
            $user=$mdlUser->getUserById($find);
            $friends[$i][1]=$user->username;
            $user_data=$mdlUserData->findUserById($find);
            foreach($user_data as $data){
                if($data->option_id==11)
                    $friends[$i][0]=$this->baseUrl().'/users/thumbs/'.$user->username;
                if($data->option_id==1)
                    $friends[$i][2]=$data->data;
                if($data->option_id==2)
                    $friends[$i][3]=$data->data;
            }
            if(!isset($friends[$i][0])){
                if($user->gender=='m')
                    $friends[$i][0]=$this->baseUrl().'/images/mt.png';
                else
                    $friends[$i][0]=$this->baseUrl().'/images/ft.png';
            }
            $i++;
        }
    }
    $toId=$mdlUser->getIdByEmail($identity->email);
    $mdlMessages = new Model_Messages();
    $unreadMessages = $mdlMessages->countUnreadMessages($toId);
    }catch (Exception $e){
//        var_dump($e->getTrace());
//        var_dump($e->getFile());
//        var_dump($e->getLine());
    }
}else{
    $loggedinusername = "";
    $identity="";
    $loggedinuserid = 0;
}
?>