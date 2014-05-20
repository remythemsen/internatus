<?php require_once(__SITE_PATH.'app/views/head.php'); ?>
<body class="install">
    <div class="row" id="setup-header" style=""></div>
    <div class="row" id="content">
        
        <div class="centered eight columns white-box">
            <div class="row">
                <h2 style="font-weight:bold;margin-bottom:-20px">Internatus CMS</h2>
                <h3>Setup Database connection</h3>
                <br />
                <?php Notifier::printAll(); ?>
                <br />
            </div>
            <div class="row">
                <form name="setup_form" id="" method="post" enctype="multipart/form-data" action="<?php echo BASE_URL.'install/database_post'; ?>">
                    <ul>
                        <li class="field">
                            <input type="text" class="narrow input" placeholder="Database Host" name="db_host"/>
                        </li>     
                        <li class="field">
                            <input type="text" class="narrow input" name="db_name" placeholder="Database Name"/>
                        </li>
                        <li class="field">
                            <input type="text" class="narrow input" placeholder="Database Username" name="db_username"/>
                        </li>
                        <li class="field">
                            <input type="password" class="narrow input" placeholder="Database Password" name="db_password"/>
                        </li>
                        <li class="field">
                            <input type="submit" class="btn primary" style="width:120px; color:white;" name="db_submit" value="Next"/>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
<?php require_once(__SITE_PATH.'app/views/footer.php'); ?>
