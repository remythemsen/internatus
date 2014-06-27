<?php namespace Internatus\Core\Helpers;

class Hash {
    public static function make($string) {
        $salt = Config::get()->general->salt;
        return SHA1($string.$salt);
    }
}