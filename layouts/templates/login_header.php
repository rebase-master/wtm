<?php
    $home=$this->url(array('module'=>'','controller'=>'home', 'action'=>'index'), null,true);
    $contact=$this->url(array('controller'=>'home', 'action'=>'contact'), null,true);
    $login=$this->url(array('controller'=>'user', 'action'=>'login'), null,true);
    $logout=$this->url(array('controller'=>'user', 'action'=>'logout'), null,true);
    $register=$this->url(array('controller'=>'user', 'action'=>'register'), null,true);
?>
    <div id="header" class="row-fluid">
        <div id="logo" class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <a href="<?php echo $home;?>">
                <img class="img-responsive" src="<?php echo $this->baseUrl();?>/images/wtm-text-heading-tpnt.png" alt="Mentors - We Change The Way You Think" />
            </a>
        </div>
        <div id="menu_holder" class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
            <?php require_once APPLICATION_PATH.'/../layouts/templates/menu.php'; ?>
        </div>
    </div>
