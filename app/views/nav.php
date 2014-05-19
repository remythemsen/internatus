<nav id="navbar-main-nav" class="navbar">
    <div class="row">
        <div class="four columns" id="main-logo">
            <a href="<?php echo BASE_URL; ?>"><?php echo Config::get()->general->pagetitle;  ?></a>
        </div>
        <nav class="eight columns navbar">
            <?php if(isset($this->account)) : ?>
            <ul id="main-menu">
                

            <ul>
            <?php endif; ?>
        </nav>
        
    </div>
</nav>
