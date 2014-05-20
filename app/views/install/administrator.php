<?php require_once(__SITE_PATH.'app/views/head.php'); ?>
<body class="install">
    <div class="row" id="setup-header" style=""></div>
    <div class="row" id="content">
        
        <div class="centered eight columns white-box">
            <div class="row">
                <h2 style="font-weight:bold;margin-bottom:-20px">Internatus CMS</h2>
                <h3>Setup Administrative Account</h3>
                <br />
                <?php Notifier::printAll(); ?>
                <br />
            </div>
            <div class="row">
                <form name="setup_user_form" id="" method="post" enctype="multipart/form-data" action="<?php echo BASE_URL.'install/administrator_post'; ?>">
                    <ul>
                        <li class="field">
                            <input type="text" class="narrow input" name="username" placeholder="Username"/>
                        </li>
                        <li class="field">
                            <input type="password" class="narrow input" placeholder="Password" name="password"/>
                        </li>
                        <li class="field">
                            <input type="password" class="narrow input" placeholder="Password Again" name="password_again"/>
                        </li>
                        <li class="field">
                            <input type="email" class="narrow input" placeholder="Email" name="email" />
                        </li>
                        <li class="field">
                            <input type="submit" class="btn primary" style="width:120px; color:white;" name="user_submit" value="Next"/>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>

<?php require_once(__SITE_PATH.'app/views/footer.php'); ?>
