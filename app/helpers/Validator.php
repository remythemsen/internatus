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

            $errors = array();

            // check for length
            if(strlen($string) < 8) {
                array_push($errors, 'Password is to short');
            }

            // check if has number
            if(!preg_match("#[0-9]+#", $string)) {
                array_push($errors, 'Password must include at least one number');
            }

            // check if has letter
            if(!preg_match("#[a-zA-Z]+#", $string)) {
                array_push($errors, 'Password must include at least one letter');
            }

            if(count($errors) == 0) {
                return true;
            } else {
                Notifier::add('warning', implode(', ', $errors));
            }
        }
    }
}