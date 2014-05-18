<nav id="navbar-main-nav" class="navbar">
    <div class="row">
        <div class="four columns" id="main-logo">
            <a href="<?php echo BASE_URL; ?>"><?php echo $this->registry->config->general->pagetitle; ?></a>
        </div>
        <nav class="eight columns navbar">
            <?php if(isset($this->account)) : ?>
            <ul id="main-menu">
                <li><a href="<?php echo BASE_URL; ?>">Dashboard</a></li>
                <li id="user-menu-item">
                    <a href="<?php echo BASE_URL; ?>account/settings"><?php echo ucfirst(Auth::account('username')); ?><i class="icon-down-open"></i></a>
                    <div class="dropdown">
                        <ul>
                            <li><a href="<?php echo BASE_URL; ?>account/settings"><i class="icon-cog"></i>Settings</a></li>
                            <li><a href="<?php echo BASE_URL; ?>account/logout"><i class="icon-logout"></i>Logout</a></li>
                        </ul>
                    </div>
                </li>
            <ul>
            <?php endif; ?>
        </nav>
        
    </div>
</nav>
