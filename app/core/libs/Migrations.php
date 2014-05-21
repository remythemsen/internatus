<?php 
class Migrations {
    private $db;
    function __construct($db) {
        $this->db = $db;
    }
    public function run() {

        $this->db->query("

            DROP TABLE IF EXISTS `accounts`;
            CREATE TABLE IF NOT EXISTS `accounts` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(25) NOT NULL,
              `password` varchar(225) NOT NULL,
              `is_admin` int(11) NOT NULL DEFAULT '0',
              `email` varchar(100) NOT NULL,
              `is_active` int(2) NOT NULL DEFAULT '0',
              `can_book` int(2) NOT NULL DEFAULT '1',
              PRIMARY KEY (`id`)
            );

            ");

    }
}