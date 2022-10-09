<?php
$mdlUser=new Model_User();
$totalUsers=$mdlUser->getUserCount();
$friends=array();
$totalFriends=0;
if (Zend_Auth::getInstance()->hasIdentity()) {
        $mdlFriends=new Model_Friends();
        $mdlUserData=new Model_UserData();
        $mdlInvitations = new Model_Invitations();

        $identity = Zend_Auth::getInstance()->getIdentity();
        $loggedinusername =$identity->username;
        $loggedinuserid = $identity->id;

        $friendRequests = $mdlInvitations->getInvitationsById($identity->id);
        $nopf = count($friendRequests);
        $userP = $identity;

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
        $toId=$mdlUser->getIdByEmail($identity->email);
        $mdlMessages = new Model_Messages();
        $unreadMessages = $mdlMessages->countUnreadMessages($toId);
}else{
    $loggedinusername = "";
    $identity="";
    $loggedinuserid = 0;
    $nopf = 0;
    $unreadMessages = 0;
}
?>
<?php
    $home = $this->url(array('module'=>'','controller'=>'home', 'action'=>'index'), null,true);
    $contact = $this->url(array('controller'=>'home', 'action'=>'contact'), null,true);
    $login = $this->url(array('controller'=>'user', 'action'=>'login'), null,true);
    $logout = $this->url(array('controller'=>'user', 'action'=>'logout'), null,true);
    $register = $this->url(array('controller'=>'user', 'action'=>'register'), null,true);
    $profile = $this->url(array('controller'=>'profile', 'action'=>'index', 'user'=>$loggedinusername), null,true);
//    $messages = 0;
    $inbox = $this->url(array('controller'=>'profile', 'action'=>'messages'), null,true);
    $confirmRequests = $this->url(array('controller'=>'profile','action'=>'confirm-requests'),null,true);

    if(isset($_SESSION['user'])){
        $sessionDp = $_SESSION['user']['dp'];
    }else{
        $sessionDp = null;
    }
?>
<!-- Google pagead starts -->
<div class="googpad">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-1662490668144398",
            enable_page_level_ads: true
        });
    </script>
</div>
<!-- Google pagead ends -->
    <div id="header" class="row-fluid">
        <div id="logo" class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <a href="<?php echo $home;?>">
                <img class="img-responsive" src="<?php echo $this->baseUrl();?>/images/wtm-text-heading-tpnt.png" alt="Mentors - We Change The Way You Think" />
            </a>
        </div>
        <div id="menu_holder" class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
            <?php require_once APPLICATION_PATH.'/../layouts/templates/menu.php'; ?>
        </div>
        <div id="shortcut" class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
            <?php if (empty($identity)) { ?>
                <div class="text-right "><a href="<?php echo $register;?>">Register</a> | <a href="<?php echo $login; ?>">Login</a></div>
            <?php } else { ?>
            <div class="ux-cont">
               <div class="user-link">
                    <a href="<?php echo $profile; ?>"><img src="<?php echo $sessionDp; ?>" title="<?php echo $loggedinusername; ?>" /> &nbsp;<b><?php echo $loggedinusername; ?></b></a>
               </div>
               <div class="ux">
                   <a href="<?php echo $confirmRequests; ?>" title="Friend Requests">
                       <?php echo $nopf>0?'<p class="frpup">'.$nopf.'</p>':''; ?>
                       <span class="glyphicon glyphicon-user"></span>
                   </a>
                   <a href="<?php echo $inbox; ?>" title="Inbox">
                    <?php echo $unreadMessages > 0 ?'<p class="msgpup">'.$unreadMessages.'</p>':''; ?>
                    <span class="glyphicon glyphicon-envelope"></span>
                </a>
                <a href="<?php echo $logout; ?>" alt="logout" title="logout"><span class="glyphicon glyphicon-off"></span></a>
               </div>
            </div>
            <?php } ?>
        </div>
    </div>
<script>
    var baseUrl = '<?php echo BASE_URL; ?>';
</script>



<!--                   <div class="fr-cont">-->
<!--                       <table class="table">-->
<!--                           --><?php //foreach($friendRequests as $friends): ?>
<!--                           --><?php
//                           $inviterPhoto = $friends['dp'];
//                           if(empty($inviterPhoto)){
//                               if($friends['gender'] == 'f'){
//                                   $src = BASE_URL.'images/ft.png';
//                               }else{
//                                   $src = BASE_URL.'images/mt.png';
//                               }
//                           }else{
//                               if(stripos($inviterPhoto, 'http') !== false)
//                                   $src = $inviterPhoto;
//                               else
//                                   $src = BASE_URL.str_replace("images", "thumbs", $inviterPhoto);
//                           }
//
//                           ?>
<!--                           <tr>-->
<!--                               <td><img src="--><?php //echo $src; ?><!--" /></td>-->
<!--                               <td class="inviter-name">-->
<!--                                   <a href="--><?php //echo BASE_URL.'profile/index/user/'.$friends['username']; ?><!--">-->
<!--                                       --><?php //echo $friends['first_name']." ".$friends['last_name']; ?>
<!--                                   </a>-->
<!--                               </td>-->
<!--                               <td class="fr-res">-->
<!--                                   <a href="javascript:void(0);" class="btn btn-danger btn-xs">Cancel</a>-->
<!--                                   <a href="javascript:void(0);" class="btn btn-primary btn-xs">Confirm</a>-->
<!--                               </td>-->
<!--                           </tr>-->
<!--                           --><?php //endforeach; ?>
<!--                       </table>-->
<!--                   </div>-->
