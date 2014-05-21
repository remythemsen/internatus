<?php 
// This is the base controller class!

Abstract Class Controller {


    function __construct() {

        // creating a new view object
        $this->view = new View();

    }
    
    // Force the index method for all controllers
    abstract function index();

}