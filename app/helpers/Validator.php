<?php

class Validator {
    public static function check($string, $type) {

        $errors = array();

        if($type == 'email') {
            // check for type: email
            if(!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                // invalid address
                array_push($errors, 'That is not a Valid email address!');
            }
        }

        if($type == 'password') {

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
        }

        if($type == 'username') {
            // check for length
            if(strlen($string) < 3) {
                array_push($errors, 'Username has to be at least 3 characters long.');
            }
        }

        // if no errors in array, then return true.
        if(count($errors) == 0) {
            return true;
        } else {
            Notifier::add('warning', implode(', ', $errors));
        }
    }
}