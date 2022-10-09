<?php
    $mdlUser            =   new Model_User();
    $identity           =   Zend_Auth::getInstance()->getIdentity();
    $loggedinusername   =   $identity->username;
    $home               =   $this->baseUrl('/admin');
    $logout             =   $this->baseUrl('/logout');
    $profile            =   $this->baseUrl('/u/'.$loggedinusername);
    $inbox              =   $this->baseUrl('/profile/messages');

    if(isset($_SESSION['user']))
        $sessionDp = $_SESSION['user']['dp'];
    else
        $sessionDp = null;

?>

<div id="header">

	<nav class="navbar navbar-default">
	    <div class="container-fluid">
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a href="<?php echo $home;?>" class="navbar-brand">
	                <img class="img-responsive" src="<?php echo $this->baseUrl('/images/logo_admin.png');?>" alt="Mentors - We Change The Way You Think" />
	            </a>
	        </div>
	        <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1" aria-expanded="false">
	            <ul class="nav navbar-nav navbar-right">
	                <li class="dropdown">
	                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	                    <?php echo $loggedinusername; ?>
	                        <span class="caret"></span>
	                    </a>
	                    <ul class="dropdown-menu ux-cont">
	                        <li><a href="<?php echo $profile; ?>" title="Profile Page">
	                                <span class="glyphicon glyphicon-user"></span>
	                                Profile
	                            </a>
	                        </li>
	                        <li><a href="<?php echo $inbox; ?>" title="Inbox">
	                                <span class="glyphicon glyphicon-envelope"></span>
	                                Messages
	                            </a>
	                        </li>
	                        <li>
	                            <a href="<?php echo $logout; ?>" title="logout">
	                                <span class="glyphicon glyphicon-off"></span>
	                                Logout
	                            </a>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
	            <div id="menu" class="nav navbar-nav navbar-center">
	                <div class="btn btn-primary">
	                    Users <span class="badge"><?php echo $this->usersCount; ?></span>
	                </div>
	                <div class="btn btn-danger">
	                    Unverified <span class="badge"><?php echo $this->verified; ?></span>
	                </div>
	                <div class="btn btn-info">
	                    Male <span class="badge"><?php echo $this->male; ?></span>
	                </div>
	                <div class="btn btn-success">
	                    Female <span class="badge"><?php echo $this->female; ?></span>
	                </div>
	                <div class="btn btn-warning">
	                    Users with DP <span class="badge"><?php echo $this->dps; ?></span>
	                </div>
	            </div>
	        </div>
	    </div>
	</nav>

</div>


<script>
    var baseUrl = '<?php echo BASE_URL; ?>';
</script>