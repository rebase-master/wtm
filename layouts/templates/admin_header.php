<?php
$home=$this->url(array('module'=>'','controller'=>'home', 'action'=>'index'), null,true);
$contact=$this->url(array('controller'=>'home', 'action'=>'contact'), null,true);
$login=$this->url(array('controller'=>'user', 'action'=>'login'), null,true);
$logout=$this->url(array('controller'=>'user', 'action'=>'logout'), null,true);
$register=$this->url(array('controller'=>'user', 'action'=>'register'), null,true);
$profile=$this->url(array('controller'=>'profile', 'action'=>'index', 'user'=>$username), null,true);
//    $messages = 0;
$inbox=$this->url(array('controller'=>'profile', 'action'=>'messages'), null,true);
?>
<div id="header" class="row-fluid">
    <div id="logo" class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
        <a href="<?php echo $home;?>">
            <img class="img-responsive" src="<?php echo $this->baseUrl();?>/images/wtm-text-heading-tpnt.png" alt="Mentors - We Change The Way You Think" />
        </a>
    </div>
    <div id="menu_holder" class="col-xs-8 col-sm-6 col-md-7 col-lg-7">
        <?php require_once APPLICATION_PATH.'/../layouts/templates/menu.php'; ?>
    </div>
    <div id="shortcut" class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
        <?php if (!$identity) { ?>
        <div class="text-right "><a href="<?php echo $register;?>">Register</a> | <a href="<?php echo $login; ?>">Login</a></div>
        <?php } else { ?>
        <div class="text-right">
            <a href="<?php echo $profile; ?>"><?php echo $username; ?></a>  |
            <a href="<?php echo $inbox; ?>">Inbox<span class="prominent"><?php echo isset($messages)?" (".$messages.")":""?></span></a> |
            <a href="<?php echo $logout; ?>">Logout</a>
        </div>
        <?php } ?>
    </div>
</div>
