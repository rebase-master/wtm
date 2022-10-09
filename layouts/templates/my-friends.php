<?php
    $allFriends = $this->url(array('controller'=>'profile','action'=>'friends', 'id'=>$this->visitor->id), null ,true);
?>
       <legend class="mf_head clearfix">
           <p class="pull-left">Friends (<?php echo $this->friendCount; ?>)</p>
           <p class="pull-right"><a href="<?php echo $allFriends; ?>">Show All</a></p>
       </legend>
        <div id="friendsLink">
            <?php if(count($this->friends)>0){ ?>
            <div class="clearfix">
            <?php foreach($this->friends as $friend){
                $name = $friend['first_name'] ." ".$friend['last_name'];
                $username = $friend['username'];
                $inviterPhoto = $friend['dp'];
                if(empty($inviterPhoto)){
                    if($friend['gender'] == 'f'){
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
                $title = $name."\n";
                if(!empty($school))
                    $title.= $school."\n";
                if(!empty($place))
                    $title.= $place;
                $userLink = $this->url(array('controller'=>'profile','action'=>'index', 'user'=>$username), null ,true);
            ?>
            <div class="my_fsp">
                <a href="<?php echo $userLink;?>">
                    <img src="<?php echo $src;?>" title="<?php echo $title; ?>" alt="<?php echo $name;?>" />
                    <p class="ellipsis"><?php echo $username; ?></p>
                </a>
            </div>
            <?php } ?>
            </div>
            <?php } ?>
        </div>
