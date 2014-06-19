<?php
// This is the base controller class!
use TheWall\Core\Helpers\URL;

Abstract Class Controller {

    function __construct() {

        // creating a new view object
        $this->view = new View();
    }
    
    // Just redirecting index to error if not overridden
    function getIndex() {
        URL::redirect('error');
    }
}