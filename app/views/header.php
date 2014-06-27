<?php
use Internatus\Core\Helpers\Notifier;

require_once(__SITE_PATH.'app/views/head.php');
?>
<body>
    <div id="content">
        <?php Notifier::printAll(); ?>

