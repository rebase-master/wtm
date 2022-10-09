<?php
    $home = $this->baseUrl('/');
    $login = $this->baseUrl('/login');
    $register = $this->baseUrl('/register');
?>
<div id="header">

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
            <ul id="menu" class="nav navbar-nav navbar-center">
                <?php require_once APPLICATION_PATH.'/../layouts/templates/menu.php'; ?>
            </ul>

        </div>
    </div>
</nav>
</div>

<script>
    var baseUrl = '<?php echo BASE_URL; ?>';
</script>