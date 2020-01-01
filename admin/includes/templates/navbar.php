
<?php  require $lang.'english.php'; //Must be the first ?>
<nav class="navbar navbar-inverse">
    <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php"><?php echo lang('home')?></a>
            </div>

            <div class="collapse navbar-collapse" id="app-nav">
            <div class="nav navbar-nav">
                <li><a href="categories.php"><?php echo lang('categories')?></a></li>
                <li><a href="items.php"><?php echo lang('items')?></a></li>
                <li><a href="members.php"><?php echo lang('members')?></a></li>
                <li><a href="comments.php"><?php echo lang('comments')?></a></li>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('adminName')?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../index.php"><?php echo lang('visitShop')?></a></li>
                        <li><a href="members.php?actionName=edit&userID=<?php echo $_SESSION['userID']?>"><?php echo lang('editProfile')?></a></li>
                        <li><a href="#"><?php echo lang('settings')?></a></li>
                        <li><a href="logout.php"><?php echo lang('logout')?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>