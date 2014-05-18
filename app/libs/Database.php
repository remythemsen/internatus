<?php

class Database extends PDO {

    // the config file
    private $config;

    public function __construct() {
        
        // retrieving the data from config file.

        $this->config = Config::get();

        parent::__construct('mysql:host='.$this->config->db->host.';charset=utf8;dbname='.$this->config->db->name,
            $this->config->db->username, $this->config->db->password
        );
    }
}
