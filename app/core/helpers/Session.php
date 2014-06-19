<?php namespace TheWall\Core\Helpers;

class Session {

    public static function init() {
        session_start();
    }

    public static function destroy() {
        session_destroy();
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    public static function regenerate() {
        session_regenerate_id(TRUE);
    }
}
