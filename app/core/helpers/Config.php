<?php

class Config {
    public static function get() {

        // retrieving the data from config file.
        if( file_exists(__SITE_PATH.'app/config/config.xml')) {
            return simplexml_load_file(__SITE_PATH.'app/config/config.xml');
        }
    }
}