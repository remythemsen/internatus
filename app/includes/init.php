<?php

// This is the initializer

// Get base the URL
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
} else {
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

if(isset($_GET['url'])) {
    // exploding the get url
    $routeParts = explode('/', $_GET['url']);
    $urlParts = explode('/', $url);

    foreach($routeParts as $routePart) {
        array_pop($urlParts);
    }

    // gathering the parts for the base URL
        
    $url = implode('/', $urlParts);
    // adding the ending slash
    $url = $url.'/';
}
    
define('BASE_URL', $url);

// require libraries and abstract base classes.

$library_dir = __SITE_PATH.'/libs/';

$libraries = glob($library_dir.'*.php', GLOB_BRACE);

foreach($libraries as $library) {
    require $library;
}

require(__SITE_PATH.'/models/abstract/Controller.php');
require(__SITE_PATH.'/models/abstract/Model.php');
require(__SITE_PATH.'/models/abstract/ViewModel.php');
require(__SITE_PATH.'/models/abstract/View.php');

// starting the session

Session::init();

// autoload the model classes.

function __autoload($class_name) {

    $filename = $class_name . '.php';
    $file = __SITE_PATH . '/models/concrete/' . $filename;

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
if(file_exists(__SITE_PATH.'/config/config.xml')) {
    $registry->config = simplexml_load_file(__SITE_PATH.'/config/config.xml');
}

// instanciating the router.
$registry->router = new Router($registry);

// setting the right path to the controllers dir.
$registry->router->setPath(__SITE_PATH . '/controllers');

// running the loader
$registry->router->loader();



?>
