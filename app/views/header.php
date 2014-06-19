<?php
use TheWall\Core\Helpers\Notifier;

require_once(__SITE_PATH.'app/views/head.php');
?>
<body>
    <div class="row" id="header">
    </div>

    <div class="row" id="content">
        <?php Notifier::printAll(); ?>

