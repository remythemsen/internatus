<?php
 Class Model {

       protected $db;

    function __construct() {
        // making the db object available from all model classes
        $this->db = new Database();
        
    }

    
}
?>
