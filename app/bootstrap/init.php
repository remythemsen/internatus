<?php

// This is the initializer

/*
 | Core
 */

require(__SITE_PATH.'app/core/App.php');

/*
 | Libraries
 */

$library_dir = __SITE_PATH.'app/libs/';

$libraries = glob($library_dir.'*.php', GLOB_BRACE);

foreach($libraries as $library) {
    require $library;
}

/*
 | Helpers
 */

$helpers_dir = __SITE_PATH.'app/helpers/';

$helpers = glob($helpers_dir.'*.php', GLOB_BRACE);

foreach($helpers as $helper) {
    require $helper;
}

// Redo
define('BASE_URL', URL::base());

/*
 | Abstract base classes
 */

require(__SITE_PATH.'app/models/abstract/Controller.php');
require(__SITE_PATH.'app/models/abstract/Model.php');
require(__SITE_PATH.'app/models/abstract/ViewModel.php');
require(__SITE_PATH.'app/models/abstract/View.php');

// starting the session

Session::init();

// autoload the model classes.

function __autoload($class_name) {

    $filename = $class_name . '.php';
    $file = __SITE_PATH . 'app/models/concrete/' . $filename;

    if (file_exists($file) == false) {
        return false;
    }
    // else include file.
    include ($file);
}


