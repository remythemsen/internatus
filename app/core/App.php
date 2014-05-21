<?php

class App {

    public function run() {

        // instanciating the registry
        // this will be the keeper of global variables.
        $registry = new Registry();

        // registering the config file
        if(file_exists(__SITE_PATH.'app/config/config.xml')) {
            $registry->config = simplexml_load_file(__SITE_PATH.'app/config/config.xml');
        }

        // instantiating the router.
        $registry->router = new Router($registry);

        // setting the right path to the controllers dir.
        $registry->router->setPath(__SITE_PATH . 'app/controllers');

        // running the loader
        $registry->router->loader();
    }
    public function detect() {

    }
} 