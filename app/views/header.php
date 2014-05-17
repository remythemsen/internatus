<?php 
require_once(__SITE_PATH.'app/views/head.php');
?>
<body>
    <?php require_once(__SITE_PATH.'app/views/nav.php'); ?>
    <div class="row" id="header">
        <div id="breadcrumbs">
        </div>
    </div>
    
    <div class="row" id="content">
        <?php $this->printNotifications(); ?>
