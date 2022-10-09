<?php
//    $allFriends = $this->url(array('controller'=>'profile','action'=>'friends', 'id'=>$this->visitor->id), null ,true);
?>
<legend class="mf_head clearfix">
    <p>People you may know</p>
</legend>
<div id="pumk-cont">
    <?php if(count($this->pumk)>0){ ?>
    <div class="clearfix">
        <?php foreach($this->pumk as $friend){
        $name = $friend['first_name'] ." ".$friend['last_name'];
        $username = $friend['username'];
        $inviterPhoto = $friend['dp'];
        if(empty($inviterPhoto)){
            if($friends['gender'] == 'f'){
                $src = BASE_URL.'images/ft.png';
            }else{
                $src = BASE_URL.'images/mt.png';
            }
        }else{
            if(stripos($inviterPhoto, 'http') !== false)
                $src = $inviterPhoto;
            else
                $src = BASE_URL.$inviterPhoto;
        }
        $city = $friend['city'];
        $country = $friend['country'];
        $place = !empty($city)?(!empty($country)?ucwords($city).", ".ucwords($country):ucwords($city)): ucwords($country);
        $school = $friend['school'];
        $userLink = $this->url(array('controller'=>'profile','action'=>'index', 'user'=>$username), null ,true);
        ?>
        <div class="list clearfix">
            <a class="col-md-4 col-sm-4 col-xs-4" href="<?php echo $userLink;?>">
                <img src="<?php echo $src;?>" title="<?php echo $name; ?>" alt="<?php echo $name;?>" />
            </a>
            <p class="ellipsis col-md-8 col-sm-8 col-xs-8"><a href="<?php echo $userLink;?>"><?php echo ucwords(strtolower($name)); ?></a><br />
            <?php if(!empty($school)): ?> <span class="school-info"><?php echo ucwords($school); ?></span><br /> <?php endif; ?>
            <span class="place-info"><?php echo ucwords($place); ?></span>
            </p>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>
