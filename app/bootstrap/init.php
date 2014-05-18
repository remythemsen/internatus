<?php

// This is the initializer

// require libraries and abstract base classes.

$library_dir = __SITE_PATH.'app/libs/';

$libraries = glob($library_dir.'*.php', GLOB_BRACE);

foreach($libraries as $library) {
    require $library;
}

require(__SITE_PATH.'app/helpers/URL.php');
define('BASE_URL', URL::base());

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

// instanciating the registry
// this will be the keeper of global variables.
$registry = new Registry();

// registering the config file
if(file_exists(__SITE_PATH.'app/config/config.xml')) {
    $registry->config = simplexml_load_file(__SITE_PATH.'app/config/config.xml');
}

require(__SITE_PATH.'app/helpers/Auth.php');
require(__SITE_PATH.'app/helpers/Router.php');
require(__SITE_PATH.'app/helpers/Config.php');
require(__SITE_PATH.'app/helpers/Notifier.php');



// instantiating the router.
$registry->router = new Router($registry);

// setting the right path to the controllers dir.
$registry->router->setPath(__SITE_PATH . 'app/controllers');

// running the loader
$registry->router->loader();



?>
