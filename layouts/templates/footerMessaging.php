<?php
    $pp=$this->url(array('controller'=>'home', 'action'=>'privacy-policy'),null,true);
    $tou=$this->url(array('controller'=>'home', 'action'=>'terms-of-use'),null,true);
?>
<div id="Two4ka1">
    <div id="footer">
              <span>
                  &copy;<?php echo strftime("%Y", time()); ?> Mentors. All Rights Reserved.
                  <a href="<?php echo $pp;?>">Privacy Policy</a> and <a href="<?php echo $tou;?>">Terms of Use</a>
              </span>
    </div>
</div>
