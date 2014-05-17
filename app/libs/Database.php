<?php

class Database extends PDO {
    
    // the config file
    private $config;

    public function __construct() {
        
        // retrieving the data from config file.
        if( file_exists(__SITE_PATH.'app/config/config.xml')) {
            $this->config = simplexml_load_file(__SITE_PATH.'app/config/config.xml');
            parent::__construct('mysql:host='.$this->config->db->host.';dbname='.$this->config->db->name,
                $this->config->db->username, $this->config->db->password
            );

        }
       
    }
}

?>
