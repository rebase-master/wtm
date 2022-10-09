<?php

class ProfileController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->_layout->setLayout('_responsive_profile_1col');
    }

    public function indexAction()
    {
//        ini_set('display_errors', 1);
        if (Zend_Auth::getInstance()->hasIdentity()) {
		    $identity = Zend_Auth::getInstance()->getIdentity();
            if($this->getRequest()->getParam('username')){
                $param = $this->_request->getParam('username');
                $filter = new Zend_Filter_Alnum();
                $param = $filter->filter($param);
                $user = new Model_User();
//                $mdlFriends = new Model_Friends();
//                $mdlInvite=new Model_Invitations();
                $mdlUserData=new Model_UserData();

                $other = $user->getUserByUsername($param);

	            $friendCount = $isSelf = $friends = $friend = $hopefulFriend = $userData = $hopefulFriend = 0;

                if(!empty($other)){
//	                var_dump($other);
//	                die;
                    $currentUser                = $user->getUserByUsername($identity->username);
//	                var_dump($currentUser);
                    $userData                   = $mdlUserData->findUserById($other->id);

	                if($identity->id == $other->id)
		                $isSelf = true;
//	                die;
                }

                $this->view->loggedUser     =   $identity;
                $this->view->visitor        =   $other;
                $this->view->param          =   $param;
	            $this->view->self           =   $isSelf;
                $this->view->userData       =   $userData;
	            $this->view->hopefulFriend  =   $hopefulFriend;

            }else{
//	            echo "param missing";
//	            die;
                $this->view->visitor = null;
            }

		}else {
            return $this->_redirect('./login');
        }
    }

    public function editAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {

            $identity = Zend_Auth::getInstance()->getIdentity();
            $editForm=new Form_EditProfile();
            $editForm->removeElement('email');
            $mdlUser=new Model_User();
            $currentUser=$mdlUser->getUserByEmail($identity->email);
            $mdlUserData=new Model_UserData();
            $cUser=$mdlUserData->findUserById($currentUser->id);
            $options=array('class','school', 'stream', 'city', 'country','about_me','ssc','hsc','graduation','post_graduation', 'dp', 'phone', 'activities');
            foreach($cUser as $cu){
                if(($cu->option_id)!=11){
                    $element=$editForm->getElement($options[$cu->option_id-1]);
                    $element->setValue($cu->data);
                }
            }
            $this->view->loggedUser=$identity;

            if($this->_request->isPost() && $editForm->isValid($_POST)){
                if(isset($_POST['cancel'])){
                    $this->_redirect('./u/'.$identity->username);
                }
                $data=$editForm->getValues();
                $mdlProfileOptions=new Model_ProfileOptions();

                $currentUser=$mdlUser->getUserByEmail($identity->email);
                $cUser=$mdlUserData->find($currentUser->id)->current();
                $mdlUserData->saveData($currentUser->id,$data);
                $this->_redirect('./u/'.$identity->username);
            }
            $this->view->form=$editForm;
    
        }else{
            $this->_redirect('./');
        }
    }

    public function changeDpAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            if (Zend_Auth::getInstance()->hasIdentity()) {
                ini_set('display_errors',1);
                $identity = Zend_Auth::getInstance()->getIdentity();
                $imageName = $_FILES['dp']['name'];
                $imageType = $_FILES['dp']['type'];
                $imageSize = $_FILES['dp']['size'];

                if (!(empty($imageType) && empty($imageSize))){
                    if ($imageSize > 5242880){
                        echo 1;
                    }else if($imageType != 'image/png' && $imageType != 'image/jpeg'){
                        echo 2;
                    }
                    $dirImage = './users/images/';
                    $dirThumb = './users/thumbs/';
                    move_uploaded_file($_FILES['dp']['tmp_name'], $dirImage.basename($imageName));
//                    copy($_FILES['dp']['tmp_name'], $dirImage.basename($imageName));
                    copy($dirImage.basename($imageName), $dirThumb.basename($imageName));

                    $this->createThumb(basename($imageName),$dirImage, 180, 200);

                    $newImageName = md5($identity->username)."_".time().".jpg";

                    $oldImage =$dirImage.basename($imageName);
                    $newImage = $dirImage.$newImageName;
                    rename($oldImage,$newImage);


                    $this->createThumb(basename($imageName),$dirThumb, 50, 50);

                    $oldThumb =$dirThumb.basename($imageName);
                    $newThumb=$dirThumb.$newImageName;
                    rename($oldThumb, $newThumb);

                    $photo = '/users/images/'.$newImageName;
                    $mdlProfileOptions=new Model_ProfileOptions();
                    $photoOptionId=$mdlProfileOptions->getIdByOptionName('display_picture');

                    $mdlUserData=new Model_UserData();
                    $mdlUserData->updatePhoto($identity->id, $photoOptionId->id, $photo);

                    $_SESSION['user']['dp'] = '/users/thumbs/'.$newImageName."?time=".time();
                    echo $newImageName;
                }else{
                    echo -9999;
                }
            }else
                echo -1;

            exit;
        }
    }

    private function createThumb($filename,$directory_path, $image_width, $image_height) {

        $temp_directory = $directory_path;

        if(preg_match('/[.](jpg)$/', $filename) || preg_match('/[.](jpeg)$/', $filename)) {
            $im = imagecreatefromjpeg($temp_directory.$filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif($temp_directory.$filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng($temp_directory.$filename);
        }

        $ox = imagesx($im);
        $oy = imagesy($im);
        $nx=$image_width;
        $ny=$image_height;

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

        imagejpeg($nm, $directory_path.$filename);
    }

}