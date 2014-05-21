<?php

class App {

    public function run() {

        // starting the session

        Session::init();

        // instantiating the router.
        $router = new Router();

        // setting the right path to the controllers dir.
        $router->setPath(__SITE_PATH . 'app/controllers');

        // running the loader
        $router->loader();
    }
    public function detect() {

    }
} 