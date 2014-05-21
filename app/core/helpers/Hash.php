<?php

class Hash {
    public static function make($string) {
        $salt = Config::get()->general->salt;
        return SHA1($string.$salt.strtolower($_POST['username']));
    }
}