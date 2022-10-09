<?php

class AppsController extends Zend_Controller_Action
{

    public function init()
    {
//      $this->_helper->_layout->setLayout('layout');
        $this->_helper->_layout->disableLayout();
    }

    public function indexAction()
    {
        // action body
    }

    public function mutualFriendsAction()
    {
        require_once APPLICATION_PATH.'/Misc/fb-php-sdk/facebook.php';

        $facebook = new Facebook(array(
            'appId'  => '569812166404771',
            'secret' => 'e13706f1d4169c1e0b961e6f5667d4d5'
        ));
        $user = $facebook->getUser();
        $loginUrl = null;
        $mutualFriends = null;

        if($user){
            $user_graph = $facebook->api(array(
                'method'    =>  'fql.query',
                'query'     =>  'SELECT uid1, uid2  FROM friend
                                   WHERE uid1 IN
                                   (SELECT uid2 FROM friend WHERE uid1=me())
                                   AND uid2 IN
                                   (SELECT uid2 FROM friend WHERE uid1=me())'
            ));

            $uid1 = array();
            foreach($user_graph as $key => $value){
                $uid1[]= $value['uid1'];
            }

            $mutualFriends = array_count_values($uid1);
            arsort($mutualFriends);
        }else{
            $loginUrl = $facebook->getLoginUrl(array(
                'redirect_uri' => 'http://wethementors.com/apps/mutual-friends'
            ));
        }
        if($this->_request->isXmlHttpRequest()){
            echo $this->_helper->json($mutualFriends);
        }else{
            $this->view->mutualFriends = $mutualFriends;
            $this->view->loginUrl = $loginUrl;
        }

        // action body
    }


}

//                echo "<div class='friend_group'>";
////                    echo "<h2>".$value['uid1']."</h2>";
//                    echo "<a href='http://facebook.com/".$value['uid1']."'><img src='http://graph.facebook.com/".$value['uid1']."/picture' alt='"."' /></a>";
//                    echo "<a href='http://facebook.com/".$value['uid2']."'><img src='http://graph.facebook.com/".$value['uid2']."/picture' alt='"."' /></a>";
//                echo "</div>";

//            print_r($mutualFriends);
//            sort($mutualFriends);
//            var_dump($mutualFriends);
//            print_r(array_unique($uid1, SORT_NUMERIC));
//            die;

//            foreach($user_graph['data'] as $key => $value){
//                echo "<div class='friend_group'>";
//                    echo "<h2>".$value['name']."</h2>";
//                    echo "<a href='http://facebook.com/".$value['id']."'><img src='http://graph.facebook.com/".$value['id']."/picture' alt='".$value['name']."' /></a>";
//                echo "</div>";
//            }
//            var_dump($user_graph);
//            $logoutUrl = $facebook->getLogoutUrl(array(
//                'redirect_uri' => 'http://localhost/wethementors/public/apps/mutual-friends'
//            ));
//            echo "<p><a href='".$logoutUrl."'>Logout</a></p>";
