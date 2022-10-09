<?php
class UserController extends Zend_Controller_Action
{
    private $redirectSession;
    public function init()
    {
        ini_set('display_errors', 1);
	    $this->_helper->_layout->setLayout('_login');
    }

    public function indexAction()
    {
        $this->_redirect('./login');
    }

    public function emailVerifyAction(){
    
    if($this->getRequest()->getParam('first_name') &&
       $this->getRequest()->getParam('last_name') &&
       $this->getRequest()->getParam('email')){
        
        $filter=new Zend_Filter_StripTags();
        $firstName=$filter->filter($this->getRequest()->getParam('first_name'));
        $lastName=$filter->filter($this->getRequest()->getParam('last_name'));
        $emailUser=$filter->filter($this->getRequest()->getParam('email'));
	    $mdlUser=new Model_User();
	    $key=$mdlUser->getRegistrationKey($emailUser);
		    if($key!=null){
			    try{
			    //create a new mail object
			    $mail=new Zend_Mail();

			    $mail->setFrom('admin@wethementors.com');
			    $mail->addTo($emailUser,
			    "{$firstName}
			    {$lastName}");
			    $mail->setSubject('Mentors - Complete Registration');
			    $verifyUrl="http://www.wethementors.com/user/verify/key/".$key;
			    include "_e-mail-verify.phtml";
			    $mail->setBodyText($email);
			    include "_html_e-mail-verify.phtml";
			    $mail->setBodyHtml($htmlbody);
			    $mail->send();

			    }catch(Zend_Exception $e){
			        $this->view->errorsMail="We were unable to send your confirmation e-mail.
			        Please contact at admin@wethementors.com.";
			    }
			    $mdlEmailUsers=new Model_EmailUsers();
			    $mdlEmailUsers->emailSent($emailUser);
		    }
	    }

	    $this->_redirect('./user/email-users');
	    $this->_helper->viewRenderer()->setNoRender();

    }
    public function emailAbsenceAction(){
    
    if($this->getRequest()->getParam('first_name') &&
       $this->getRequest()->getParam('last_name') &&
       $this->getRequest()->getParam('email')){
        
        $filter=new Zend_Filter_StripTags();
        $firstName=$filter->filter($this->getRequest()->getParam('first_name'));
        $lastName=$filter->filter($this->getRequest()->getParam('last_name'));
        $emailUser=$filter->filter($this->getRequest()->getParam('email'));
        
    try{
    //create a new mail object
    $mail=new Zend_Mail();
    
    $mail->setFrom('admin@wethementors.com');
    $mail->addTo($emailUser,
    "{$firstName}
    {$lastName}");
    $mail->setSubject('Mentors - Your attendance is short.');
    include "_e-mail-absence7.phtml";
    $mail->setBodyText($email);
    include "_html_e-mail-absence7.phtml";
    $mail->setBodyHtml($htmlbody);
    $mail->send();
    
    }catch(Zend_Exception $e){
        $this->view->errorsMail="We were unable to send your confirmation e-mail.
        Please contact at admin@wethementors.com.";
    }
    $mdlEmailUsers=new Model_EmailUsers();
    $mdlEmailUsers->emailSent($emailUser);
    $this->_redirect('./user/email-users');
    }
    
    $this->_helper->viewRenderer()->setNoRender();
    }
        public function emailNotificationAction(){
    
    if($this->getRequest()->getParam('first_name') &&
       $this->getRequest()->getParam('last_name') &&
       $this->getRequest()->getParam('email')){
        
        $filter=new Zend_Filter_StripTags();
        $firstName=$filter->filter($this->getRequest()->getParam('first_name'));
        $lastName=$filter->filter($this->getRequest()->getParam('last_name'));
        $emailUser=$filter->filter($this->getRequest()->getParam('email'));
        
    try{
    //create a new mail object
    $mail=new Zend_Mail();
    $year = date("Y");
    $mail->setFrom('admin@wethementors.com');
    $mail->addTo($emailUser,
    "{$firstName}
    {$lastName}
    {$year}
    ");
    $mail->setSubject('Important/Guess Computer Practical Questions '.$year);
    include "_e-mail-comp-practical.phtml";
    $mail->setBodyText($email);
    include "_html_e-mail-comp-practical.phtml";
    $mail->setBodyHtml($htmlbody);
    $mail->send();
    
    }catch(Zend_Exception $e){
        $this->view->errorsMail="Something went wrong while we tried to send the email.";
    }
    $this->_redirect('./user/email-users');
    }
    
    $this->_helper->viewRenderer()->setNoRender();
    }

    private function validateData($userData)
    {
        $errors=array();
        $validEmail=new Zend_Validate_EmailAddress();
        if(!$validEmail->isValid($userData['email']))
            $errors[]="Invalid Email Address";
        $user=new Model_User();
        $foundUser=$user->getUserByEmail($userData['email']);
        if($foundUser!=null)
           $errors[]="Email already exist. Please choose a different email id.";
        $validUsername=new Zend_Validate_StringLength(2,20);
        if(!$validUsername->isValid($userData['username'])){
            $errors[]="Username must be between 2 to 20 characters";
        }
        $alnum=new Zend_Validate_Alnum();
        if(!$alnum->isValid($userData['username'])){
            $errors[]="Username must consist of letters and numbers only.";
        }
        if($user->getUserByUsername($userData['username'])!=""){
            $errors[]="Username already exists.";
        }
        $validPwdlen=new Zend_Validate_StringLength(6,20);
        if(!$validPwdlen->isValid($userData['password'])){
            $errors[]="Password must be atleast 6 characters long.";
        }
        $empty=new Zend_Validate_NotEmpty();
        if(!$empty->isValid($userData['first_name'])){
            $errors[]="Please provide your first name.";
        }
        $alpha=new Zend_Validate_Alpha();
        if(!$alpha->isValid($userData['first_name'])){
            $errors[]="First name should only contain letters.";
        }
        if(!$empty->isValid($userData['last_name'])){
            $errors[]="Please provide your last name.";
        }
        $alpha=new Zend_Validate_Alpha();
        if(!$alpha->isValid($userData['last_name'])){
            $errors[]="Last name should only contain letters.";
        }
        if($userData['password']!=$userData['retyped_password']){
            $errors[]="Password and Retyped Password do not match.";
        }
        return $errors;        
    }
    public function registerFacebookAction(){
        $params = $this->getRequest()->getParams();
        $mdlUser=new Model_User();
        $email = $params['email'];
        $existingUser = $mdlUser->getUserByEmail($email);

        require_once APPLICATION_PATH.'/Misc/FacebookV3/base_facebook.php';
        require_once APPLICATION_PATH.'/Misc/FacebookV3/Facebook.php';

            $facebook = new Facebook(array('appId' => '1436848579896974', 'secret' => '4f8927f257e67fb18e68106eb80e6928'));
            $facebook->setExtendedAccessToken();
            $user = $facebook->api('/me');
            $access_token = $facebook->getAccessToken();
            $params['access_token'] = $access_token;

        if(!empty($existingUser)){
            $id = $mdlUser->updateAccessToken($access_token, $email, 'facebook');
        }else{
            $id = $mdlUser->createUser($params, 'facebook');
            $photoSource = $params['dp'];
            $mdlUserData = new Model_UserData();
            $mdlUserData->updatePhoto($id,11,$photoSource);
        }

        if($id != -1){

            try{

                $db=Zend_Db_Table::getDefaultAdapter();
                //create the auth adapter
                $authAdapter=new Zend_Auth_Adapter_DbTable(
                    $db,'users','email','password');
                $authAdapter->setIdentity($params['email']);
                $updateLogin=$mdlUser->getUserByEmail($params['email']);
                $authAdapter->setCredential($updateLogin->password);
                $result=$authAdapter->authenticate();
                if($result->isValid()){

                    $auth=Zend_Auth::getInstance();
                    if($updateLogin->confirmed==1){
                        $updateLogin->last_login=date('Y-m-d H:i:s',time()+5.5*60*60);
                        $updateLogin->save();
                        $storage=$auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(
                            array('id','username','gender','first_name','last_name','email','last_login')));
                    }
                }
            }catch (Exception $e){
                Phototour_Logger::log($e);
            }

            echo $updateLogin->username;
        }else{
                echo -1;
            }
        exit;
    }
    public function registerSocialAction(){

       try{

        $mdlUser=new Model_User();
        $data = array();
        $params = $this->getRequest()->getParams();
        $photoSource = $dp = $params['dp'];
        $email = $params['email'];
        $type = $params['type'];

           if($type == 'facebook'){

               require_once APPLICATION_PATH.'/Misc/FacebookV3/base_facebook.php';
               require_once APPLICATION_PATH.'/Misc/FacebookV3/Facebook.php';

               $facebook = new Facebook(array('appId' => '1436848579896974', 'secret' => '4f8927f257e67fb18e68106eb80e6928'));
               $facebook->setExtendedAccessToken();
               $user = $facebook->api('/me');
               $access_token = $facebook->getAccessToken();

           }else if($type == 'google'){

               require_once APPLICATION_PATH.'/Misc/GoogleAPI/autoload.php';
               $client = new Google_Client();
               $code= $params['code'];

               $client->setApplicationName("Mentors");
               $client->setDeveloperKey("AIzaSyCLOBbR5yU5rk-J9Rr0z3Sjmpnw-1ldRVg");
               $client->setClientId('105054242727-pa99gspqk1oigt8d5p3b0rdlnjq9enam.apps.googleusercontent.com');
               $client->setClientSecret('LtTPPaGSFrDShiqjwE5yXAA6');
               $client->setRedirectUri('postmessage');
               $client->authenticate($code);
               $token = json_decode($client->getAccessToken());
               $access_token = $token->access_token;

               $index = strrpos($dp, "?");
               $photoSource =  $index === false? $dp : substr($dp, 0, $index);

           }else{
               $access_token= null;
           }


        $existingUser = $mdlUser->getUserByEmail($email);

        if(!empty($existingUser)){
            $id = $mdlUser->updateAccessToken($access_token, $email, $type);
            $mdlUserData = new Model_UserData();
            $mdlUserData->updatePhoto($id,11,$photoSource);
        }else{
            $data['access_token'] = $access_token;
            $data['verified'] = 1;
            $data['email'] = $email;
            $data['gender'] = $params['gender'];
            $data['first_name'] = $params['first_name'];
            $data['last_name'] = $params['last_name'];
            $data['username'] = $params['username'];
            $data['password'] = $params['key'];
            $id = $mdlUser->createUser($data, $type);
            $mdlUserData = new Model_UserData();
            $mdlUserData->updatePhoto($id,11,$photoSource);
        }

        if($id != -1){

            try{

                $db=Zend_Db_Table::getDefaultAdapter();
                //create the auth adapter
                $authAdapter=new Zend_Auth_Adapter_DbTable(
                    $db,'users','email','password');
                $authAdapter->setIdentity($params['email']);
                $updateLogin=$mdlUser->getUserByEmail($params['email']);
                $authAdapter->setCredential($updateLogin->password);
                $result=$authAdapter->authenticate();
                if($result->isValid()){
                    $dp = $mdlUserData->getSessionPhotoById($updateLogin['id'], $updateLogin['gender']);
                    $sessionUser = new Zend_Session_Namespace('user');
                    $sessionUser->dp = $dp;

                    $auth=Zend_Auth::getInstance();
                    if($updateLogin->confirmed==1){
                        $updateLogin->last_login=date('Y-m-d H:i:s',time()+5.5*60*60);
                        $updateLogin->save();
                        $storage=$auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(
                            array('id','username','gender','first_name','last_name','email','last_login')));
                    }
                }
            }catch (Exception $e){
                Phototour_Logger::log($e);
            }

            echo $updateLogin->username;
        }else{
                echo -1;
            }
       }catch(Exception $e){
           Phototour_Logger::log("Google API error: ".$e);
//           echo -999;
       }
        exit;
    }
    public function usernameExistsAction(){

        $username = $this->getRequest()->getParam('username');
        $mdlUser = new Model_User();
        $result = $mdlUser->getUserByUsername($username);

        if(empty($result))
            echo -1;
        else
            echo 1;
        exit;
    }
    public function userExistsAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $email = $this->getRequest()->getParam('email');
            $type = $this->getRequest()->getParam('type');
            $mdlUser = new Model_User();
            try{
                $userExists = $mdlUser->userExists($email);
            }catch(Exception $e){
                $userExists = "error";
            }
            if($userExists == 'error')
                echo -999;
            else if(empty($userExists))
                echo -1;
            else if($userExists->type == $type)
                echo 2;
            else
                echo 1;

            exit;
        }
    }

    public function socialLoginAction(){
         ini_set('display_errors', 1);
        $email = $this->getRequest()->getParam('email');
        $mdlUser = new Model_User();
        $user = $mdlUser->getUserByEmail($email);
        $db=Zend_Db_Table::getDefaultAdapter();
        //create the auth adapter
        $authAdapter=new Zend_Auth_Adapter_DbTable(
            $db,'users','email','password');
        $authAdapter->setIdentity($user->email);
        $authAdapter->setCredential($user->password);
        $result=$authAdapter->authenticate();
        if($result->isValid()){
            $mdlUserData = new Model_UserData();
            $dp = $mdlUserData->getSessionPhotoById($user['id'], $user['gender']);
            $sessionUser = new Zend_Session_Namespace('user');
            $sessionUser->dp = $dp;

            $auth=Zend_Auth::getInstance();
            if($user->confirmed==1){
                $user->last_login=date('Y-m-d H:i:s',time()+5.5*60*60);
                $user->save();
                $storage=$auth->getStorage();
                $storage->write($authAdapter->getResultRowObject(
                    array('id','username','gender','first_name','last_name','email','last_login')));
                echo $user->username;
                exit;
            }else{
                echo -1;
            }

        }
        echo -1;
        exit;
    }

    public function registerAction(){
        $userForm=new Form_User();
        $userForm->getElement('email')->addErrorMessage('Please enter a valid e-mail address. E.g. bhagatsingh@india.com'); 
        $userForm->getElement('password')->addErrorMessage('Password must be between 6 to 15 characters');

	    //        $this->view->APP_ID =  $app = '1436848579896974';
	    $this->view->APP_ID =  $app = '1436848579896974';
	    $this->view->GOOGLE_CLIENT_ID =  $app = '105054242727-pa99gspqk1oigt8d5p3b0rdlnjq9enam.apps.googleusercontent.com';
	    $this->view->GOOGLE_API_KEY =  $app = 'AIzaSyCLOBbR5yU5rk-J9Rr0z3Sjmpnw-1ldRVg';

	    if($this->getRequest()->isPost()){
            if($userForm->isValid($_POST)){
                $userData=$userForm->getValues();
                $errors=$this->validateData($userData);
                if(count($errors)>0){
                 $this->view->errors=$errors;
                 $this->view->submitted=0;
                }else{
                    $this->view->submitted=1;
                    $mdlUser=new Model_User();
                    $result=$mdlUser->createUser($userData);
                    if($result){
                    $this->view->success="Your form has been successfully submitted.<br />
                    Now verify your account by clicking on the link that is sent to the email
                    provided by you.";
                    try{
                      
                        $mail=new Zend_Mail();
                        $mail->setFrom('registration@wethementors.com');
                        $mail->addTo($userData['email'],
                        "{$userData['first_name']}
                        {$userData['last_name']}");
                        $mail->setSubject('Wethementors: Please confirm your registration');
                        $firstName=$userData['first_name'];
                        $verifyUrl="http://www.wethementors.com/user/verify/key/".$result;
                        include "_e-mail-confirm-registration.phtml";
                        $mail->setBodyText($email);
                        include "_html_e-mail-confirm-registration.phtml";
                        $mail->setBodyHtml($htmlbody);
                        $mail->send();
                    }catch(Zend_Exception $e){
                        $this->view->errorsMail="We were unable to send your confirmation e-mail.
                        Please contact at contact@wethementors.com.";
                    }
                }else{
                        $this->view->success="Unable to create your account. Please try again later.";
                    }
                }
            }
        }
        $userForm->setAction('./register');
        $this->view->form=$userForm;
    }
    public function verifyAction(){
       $this->view->pageTitle="Complete the Registration Process.";
       $registationKey=$this->_request->getParam('key');
       $registationKey=htmlspecialchars($registationKey);
       $mdlUser=new Model_User();
       $result=$mdlUser->confirmRegistration($registationKey);
       if($result){
        $this->view->success=1;
        }else{
            $this->view->errors="We were unable to locate your registration key.";
        }
    }
    public function loginAction(){

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            $username=$identity->username;
            $this->_redirect('/u/'.$username);
        }

//        $this->view->APP_ID =  $app = '1436848579896974';
        $this->view->APP_ID =  $app = '1436848579896974';
        $this->view->GOOGLE_CLIENT_ID =  $app = '105054242727-pa99gspqk1oigt8d5p3b0rdlnjq9enam.apps.googleusercontent.com';
        $this->view->GOOGLE_API_KEY =  $app = 'AIzaSyCLOBbR5yU5rk-J9Rr0z3Sjmpnw-1ldRVg';

        if($this->getRequest()->getParam('next')){
            $session = new Zend_Session_Namespace('login_next');
            $session->next = $this->getRequest()->getParam('next');
        }

        if($this->_request->isPost()){
            $data = $this->getRequest()->getParams();
            $db = Zend_Db_Table::getDefaultAdapter();
            //create the auth adapter
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                                $db,'users','email','password');
            $email = htmlspecialchars($data['email']);
            $password = htmlspecialchars($data['password']);
            if(empty($email) || empty($password)){
                $this->view->loginMessage='Please enter your email and password to log in.';
            }else{
                $authAdapter->setIdentity($email);
                $authAdapter->setCredential(sha1($password));
                $result=$authAdapter->authenticate();
                if($result->isValid()){
                    $auth=Zend_Auth::getInstance();
                    $mdlUser=new Model_User();
                    $mdlUserData = new Model_UserData();

                    $updateLogin=$mdlUser->getUserByEmail($email);
                    if($updateLogin->confirmed==1){
                        $updateLogin->last_login = date('Y-m-d H:i:s',time()+5.5*60*60);
                        $updateLogin->save();
                        $storage=$auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(
                            array('id','username','admin','access','gender','first_name','last_name','email','last_login')));
                        $dp = $mdlUserData->getSessionPhotoById($updateLogin['id'], $updateLogin['gender']);
                        $sessionUser = new Zend_Session_Namespace('user');
                        $sessionUser->dp = $dp;
                        $sessionUser->admin = $updateLogin['admin'];
                        $session=new Zend_Session_Namespace('login_next');

                        if(isset($session) && !empty($session->next)){
                            $url = $session->next;
                            Zend_Session::namespaceUnset('login_next');
                            return $this->_redirect($url);
                        }else{
	                        if($sessionUser->admin == true){
		                        return $this->_redirect('./admin');
	                        }else{
		                        return $this->_redirect('./u/'.$updateLogin->username);
	                        }
                        }

                    }else{
                        $this->view->loginMessage='The e-mail associated with this account has not been confirmed.';
                    }
                }else{
                    $this->view->loginMessage='E-mail or password is incorrect.';
                }
            }
        }
    }
    public function logoutAction()
    {
        $authAdapter=Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
        session_destroy();
        Zend_Session::namespaceUnset('user');
        Zend_Session::namespaceUnset('login_next');
        $this->_redirect('/login');
    }
    public function passwordRecoveryAction()
    {
        $this->view->pageTitle="Reset your password.";
        $form=new Zend_Form();
        $email=$form->createElement('text','email');
        $email->setLabel('Enter your Email: ');
        $email->setAttrib('size','25');
        $email->setAttrib('class','form-control');
        $email->setRequired(true);
        $form->addElement($email);
        $submit= $form->createElement('submit','submit');
        $submit->setLabel('Submit');
        $submit->setAttrib('class', 'btn btn-primary btn-lg');
        $form->addElement($submit);

        if($this->_request->isPost() && $form->isValid($_POST)){
            $email=htmlspecialchars($form->getValue('email'));
            $mdlUser=new Model_User();
            $result=$mdlUser->getUserByEmail($email);
            if($result==null || $result->confirmed==0){
                $this->view->error="There is no account associated with this e-mail or the e-mail has not been verified.";
            }else{
                $registrationKey=$mdlUser->generate_random_string();
                $result->registration_key=$registrationKey;
                $result->save();
                    try{
                        $mail=new Zend_Mail();
                        $mail->setFrom('registration@wethementors.com');
                        $mail->addTo($result->email,"{$result->first_name}{$result->last_name}");
                        $mail->setSubject('wethementors: Reset Password');
                        $verifyUrl="http://www.wethementors.com/user/reset/key/".$registrationKey;
                        include "_e-mail-forgot-password.phtml";
                        $mail->setBodyText($email);
                        include "_html_e-mail-forgot-password.phtml";
                        $mail->setBodyHtml($htmlbody);
                        $mail->send();
                        $this->view->success=1;
                        
                    }catch(Zend_Exception $e){
                        $this->view->error="We were unable to send you the password reset link on your e-mail.
                        Please contact us at contact@wethementors.com.";
                    }
            }
        }
        $form->setAction('./password-recovery');
        $this->view->form=$form;
    }
    public function resetAction()
    {
            if($this->_request->getParam('key'))
            $registrationKey=strip_tags($this->_request->getParam('key'));
            $registrationKey=htmlspecialchars($registrationKey);
            $mdlUser=new Model_User();
            $result=$mdlUser->confirmRegistration($registrationKey);
            if($result){
                $session=new Zend_Session_Namespace('session');
                $session->key=$registrationKey;
             $this->_redirect('./user/reset-password');
             }else{
                 $this->view->errors="We were unable to locate your registration key.";
            } 
    }
    public function resetPasswordAction()
    {
        $form=new Form_PasswordForm();
        $form->setAction('./reset-password');
       if($this->_request->isPost()){
            if($form->isValid($_POST)){
            try{    
                $session=new Zend_Session_Namespace('session');
                $registrationKey=$session->key;
            }catch(Zend_Exception $e){
                $this->view->error="Cannot find the key.";
            }
            $mdlUser=new Model_User();
            $result=$mdlUser->confirmRegistration($registrationKey);

            if($form->getValue('password1')==$form->getValue('password2')){
                $result->password=sha1(htmlspecialchars($form->getValue('password1')));
                $result->save();
                $this->view->updated=1;
            }else{
                $this->view->errors="The passwords did not match.";
            }
           }
        }
        $this->view->form=$form;
    }
}