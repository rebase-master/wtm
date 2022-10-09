<?php

	if (Zend_Auth::getInstance()->hasIdentity()) {
		$identity = Zend_Auth::getInstance()->getIdentity();
	}
	$admin = $this->baseUrl('/admin');
    $home = $this->baseUrl('/');
    $login = $this->baseUrl('/login');
    $logout = $this->baseUrl('/logout');
    $register = $this->baseUrl('/register');

    $sessionDp = isset($_SESSION['user']) ? $_SESSION['user']['dp'] : null;

?>

<div id="header" class="navbar-fixed-top1">

<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?php echo $home;?>" class="navbar-brand">
                <img class="img-responsive" src="<?php echo $this->baseUrl('/images/logo.png');?>" alt="Mentors - We Change The Way You Think" />
            </a>
        </div>

        <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1" aria-expanded="false">
            <ul class="nav navbar-nav navbar-right user-nav">

	            <?php if(empty($identity)): ?>

	                <li><a class="unalinks" href="<?php echo $login; ?>">Login</a></li>
                    <li><a class="btn btn-danger unalinks"  href="<?php echo $register;?>">Sign Up</a></li>

                <?php else: ?>
		            <?php
				            $loggedinusername = $identity->username;
				            $profile = $this->baseUrl('/u/'.$loggedinusername);
		            ?>
	                <li class="dropdown">
	                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	                    <?php echo $loggedinusername; ?>
	                        <span class="caret"></span>
	                    </a>
	                    <ul class="dropdown-menu ux-cont">
		                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['admin'] == true): ?>
		                        <li><a href="<?php echo $admin; ?>">
		                                <span class="glyphicon glyphicon-tower"></span>
		                                Admin
		                            </a>
		                        </li>
			                    <li role="separator" class="divider"></li>
		                    <?php endif; ?>

	                        <li><a href="<?php echo $profile; ?>" title="Profile Page">
	                                <span class="glyphicon glyphicon-user"></span>
	                                Profile
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
                <?php endif; ?>

            </ul>

            <ul id="menu" class="nav navbar-nav navbar-center">
                <?php require_once APPLICATION_PATH.'/../layouts/templates/menu.php'; ?>
            </ul>

        </div>
    </div>


</nav>
</div>

<div id="sub-header">
	<?php

		$mdlNotesCategories = new Model_NotesCategory();
		$categories         = $mdlNotesCategories->readCategories(true, 'subject');

	?>


	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
				<ul class="nav navbar-nav">
					<?php foreach ($categories as $index => $category): ?>
						<?php $activeClass = (isset($this->subject) && strcmp(strtolower($this->subject), $category['category']) == 0)? 'active': ''; ?>
						<?php $colors = array('danger','primary','success','info', 'warning'); ?>
						<li class="<?php echo $activeClass; ?>">
							<a href="<?php echo $this->baseUrl('notes/'.$category['category_slug']); ?>" class="btn1-<?php echo $colors[($index+1)%5]; ?>">
								<?php echo ucwords($category['category']); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</div>


<script>
    var baseUrl = '<?php echo BASE_URL; ?>';
    var fbAppId = '<?php echo FACEBOOK_APP_ID; ?>';
</script>