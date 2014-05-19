<?php

class Validator {
    public static function check($string, $type) {
        if($type == 'email') {
            // check for type: email
            if(filter_var($string, FILTER_VALIDATE_EMAIL)) {
                // valid address
                return true;
            }
        }

        if($type == 'password') {
            // check for length
            if(strlen($string) >= 8) {
                // TODO: check for complexity


                return true;
            }
        }
    }
}