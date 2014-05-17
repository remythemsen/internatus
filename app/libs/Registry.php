<?php

Class Registry {

// this is where values and objects used across the entire application is stored.
 
    private $vars = array();

    // php magic set method
    public function __set($index, $value) {
        $this->vars[$index] = $value;
    }
    
    // php magic get method.
    public function __get($index) {
        return $this->vars[$index];
    }
    
    // check if the key exists.
    public function __isset($index) {

        $result = array_key_exists($index, $this->vars);

        if ($result) {
            return true;
        } 
    }
}

?>
