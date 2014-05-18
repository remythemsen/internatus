<?php 
/* This is the entry point of the web application */

// Error Reporting: 
error_reporting(E_ALL); // on

// define the site path constant 
$site_path = preg_replace('/public$/', '', realpath(dirname(__FILE__)));

define ('__SITE_PATH', $site_path);

/* For running migrations file
require(__SITE_PATH.'app/includes/migrations.php');
require(__SITE_PATH.'app/libs/Database.php');
$migration = new Migrations(new Database());
$migration->run();
*/

// loading the initializer.
require __SITE_PATH.'app/includes/init.php';


