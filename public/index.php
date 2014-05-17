<?php 
/* This is the entry point of the web application */

// Error Reporting: 
error_reporting(E_ALL); // on

// define the site path constant 
$site_path = preg_replace('/public$/', '', realpath(dirname(__FILE__)));

define ('__SITE_PATH', $site_path);

// loading the initializer.
require __SITE_PATH.'app/includes/init.php';

?>
