<?php

// This is the initializer

/*
 | App class
 */

require(__SITE_PATH.'app/core/App.php');

/*
 | Libraries
 */

foreach (glob(__SITE_PATH.'app/core/libs/*.php') as $filename)
{
    require $filename;
}

/*
 | Helpers
 */

foreach (glob(__SITE_PATH.'app/core/helpers/*.php') as $filename)
{
    require $filename;
}

// Redo
define('BASE_URL', URL::base());

/*
 | Abstract base classes
 */

foreach (glob(__SITE_PATH.'app/core/base/*.php') as $filename)
{
    require $filename;
}

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


