<?php
    $pp  = $this->baseUrl('/home/privacy-policy');
    $tou = $this->baseUrl('/home/terms-of-use');
?>
<div id="Two4ka1">
    <div id="footer" class="text-center">
              <span>
                  &copy;<?php echo strftime("%Y", time()); ?> Mentors. All Rights Reserved.
                  <a href="<?php echo $pp;?>">Privacy Policy</a> and <a href="<?php echo $tou;?>">Terms of Use</a>
              </span><br />
              <span>
                  <script type="text/javascript">document.write(Date());</script>
              </span>
    </div>
</div>
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css' media="all" />
<link href="https://fonts.googleapis.com/css?family=Roboto:700,500" rel="stylesheet">

<?php require_once APPLICATION_PATH.'/../layouts/templates/google-analytics.php'; ?>
