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

// TODO: Find a new place for this definition
use TheWall\Core\Helpers\URL;
define('BASE_URL', URL::base());

/*
 | Abstract base classes
 */

foreach (glob(__SITE_PATH.'app/core/base/*.php') as $filename)
{
    require $filename;
}

// Include the main Propel script
require_once __SITE_PATH.'vendor/propel/propel1/runtime/lib/Propel.php';

// Initialize Propel with the runtime configuration
Propel::init(__SITE_PATH."build/conf/thewall-conf.php");

// Add the generated 'classes' directory to the include path
set_include_path(__SITE_PATH."build/classes" . PATH_SEPARATOR . get_include_path());

