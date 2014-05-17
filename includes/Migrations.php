<?php 
class Migrations {
    private $db;
    function __construct($db) {
        $this->db = $db;
    }
    public function run() {

        $this->db->query("
            DROP PROCEDURE IF EXISTS `count_comments`;
            CREATE PROCEDURE `count_comments`( In roomId INT(32))
            BEGIN
                SELECT COUNT(*)
                FROM comments
                WHERE comments.room_id = roomId;
            END;
            
            DROP TABLE IF EXISTS `users_inserted`;
            CREATE TABLE IF NOT EXISTS `users_inserted` (
                `last_inserted` TIMESTAMP
            );

            DROP TABLE IF EXISTS `rooms`;

            CREATE TABLE IF NOT EXISTS `rooms` (
              `id` int(55) NOT NULL AUTO_INCREMENT,
              `name` varchar(36) NOT NULL,
              `description` text NOT NULL,
              `booking_count` int(32) NOT NULL DEFAULT '0',
              `status` int(2) NOT NULL DEFAULT '1',
              PRIMARY KEY (`id`)
          );
            DROP TABLE IF EXISTS `users`;
            CREATE TABLE IF NOT EXISTS `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(25) NOT NULL,
              `password` varchar(32) NOT NULL,
              `is_admin` int(11) NOT NULL DEFAULT '0',
              `email` varchar(100) NOT NULL,
              `is_active` int(2) NOT NULL DEFAULT '0',
              `can_book` int(2) NOT NULL DEFAULT '1',
              PRIMARY KEY (`id`)
          );
            DROP TABLE IF EXISTS `bookings` ;

            CREATE TABLE IF NOT EXISTS `bookings` (
              `id` INT(30) NOT NULL AUTO_INCREMENT,
              `time_from` DATETIME NOT NULL,
              `time_to` DATETIME NOT NULL,
              `user_id` INT(11) NOT NULL,
              `room_id` INT(55) NOT NULL,
              PRIMARY KEY (`id`),
              INDEX `fk_bookings_users1_idx` (`user_id` ASC),
              INDEX `fk_bookings_rooms1_idx` (`room_id` ASC),
              CONSTRAINT `fk_bookings_users1`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO CASCADE
                ON UPDATE NO CASCADE,
              CONSTRAINT `fk_bookings_rooms1`
                FOREIGN KEY (`room_id`)
                REFERENCES `rooms` (`id`)
                ON DELETE NO CASCADE
                ON UPDATE NO CASCADE
            );

            DROP TABLE IF EXISTS `comments` ;

            CREATE TABLE IF NOT EXISTS `comments` (
              `id` INT(32) NOT NULL AUTO_INCREMENT,
              `comment` TEXT NOT NULL,
              `post_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `user_id` INT(11) NOT NULL,
              `room_id` INT(55) NOT NULL,
              PRIMARY KEY (`id`),
              INDEX `fk_comments_users_idx` (`user_id` ASC),
              INDEX `fk_comments_rooms1_idx` (`room_id` ASC),
              CONSTRAINT `fk_comments_users`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO CASCADE
                ON UPDATE NO CASCADE,
              CONSTRAINT `fk_comments_rooms1`
                FOREIGN KEY (`room_id`)
                REFERENCES `rooms` (`id`)
                ON DELETE NO CASCADE
                ON UPDATE NO CASCADE
            );
            
            DROP VIEW IF EXISTS `top_3_rooms`;
            CREATE VIEW `top_3_rooms` 
            AS select `rooms`.`id` 
            AS `id`,`rooms`.`name`
            AS `name`,`rooms`.`booking_count` 
            AS `booking_count` from `rooms` where (`rooms`.`status` <> 0) order by `rooms`.`booking_count` desc limit 3;

            DROP TRIGGER IF EXISTS `insert_attempt`;
            CREATE TRIGGER `insert_attempt` AFTER INSERT ON `users` 
            FOR EACH ROW INSERT INTO `users_inserted` VALUES(DEFAULT);

            ");

    }
}
?>
