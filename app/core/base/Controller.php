<?php 
// This is the base controller class!

Abstract Class Controller {

    protected $registry;

    function __construct($registry) {

        // storing the global registry.
        $this->registry = $registry;

        // creating a new view object
        $this->view = new View($this->registry);

    }
    
    // Force the index method for all controllers
    abstract function index();

}