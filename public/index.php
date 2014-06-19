<?php

use TheWall\Core;


// hide system errors
error_reporting(null);

/* This is the entry point of the web application */

// Set site path
define('__SITE_PATH', preg_replace('/public$/', '', realpath(dirname(__FILE__))));

// Run init
require(__SITE_PATH.'app/bootstrap/init.php');

// Get app object
$app = new Core\App();

// Start application
$app->run();
