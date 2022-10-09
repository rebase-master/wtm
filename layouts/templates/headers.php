<div id="header-group">
    <div id="header" class="row-fluid">
        <div id="topbar">
                <?php
                $home=$this->url(array('module'=>'','controller'=>'home', 'action'=>'index'), null,true);
                $contact=$this->url(array('controller'=>'home', 'action'=>'contact'), null,true);
                $login=$this->url(array('controller'=>'user', 'action'=>'login'), null,true);
                $logout=$this->url(array('controller'=>'user', 'action'=>'logout'), null,true);
                $register=$this->url(array('controller'=>'user', 'action'=>'register'), null,true);
                $profile=$this->url(array('controller'=>'profile', 'action'=>'index', 'user'=>$username), null,true);
                $inbox=$this->url(array('controller'=>'profile', 'action'=>'messages'), null,true);
                ?>
                <div class="col-xs-4">
                    <a href='<?php echo $home;?>'>Home </a>|
                    <a href='<?php echo $contact;?>'>Contact </a>
                </div>
                <?php if (!$identity) { ?>
                <div class="col-xs-8 text-right"><a href="<?php echo $register;?>">Register</a> | <a href="<?php echo $login; ?>">Login</a></div>
                <?php } else { ?>
                <div class="col-xs-8 text-right">
                    <a href="<?php echo $profile; ?>"><?php echo $username; ?></a>  |
                    <!--                <a href="--><?php //echo $inbox; ?><!--">Messages<span class="prominent">--><?php //echo isset($messages)?$messages:""?><!--</span></a> |-->
                    <a href="<?php echo $logout; ?>">Logout</a>
                </div>
                <?php } ?>
        </div>
    </div>
    <div id="logobar" class="row-fluid clearfix">
        <div id="logo" class="col-xs-3 col-md-3 col-lg-3"><a href="<?php echo $home;?>"><img src="<?php echo $this->baseUrl();?>/images/heading.png" alt="Mentors - We Change The Way You Think" /></a></div>
        <div id="banner-ad" class="col-xs-9 col-md-9 col-lg-9 text-center">
    <!--        --><?php //require_once APPLICATION_PATH.'/../layouts/templates/google-ad.php'; ?>
        </div>
    </div>
    <div id="menu_holder" class="">
        <?php require_once APPLICATION_PATH.'/../layouts/templates/menu.php'; ?>
    </div>
</div>