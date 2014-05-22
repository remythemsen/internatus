<?php

class Validator {
    public static function check($array) {

        // Accepts Associate Array, keys: username, password, email.

        $errors = array();
        $db = new Database();


        if(array_key_exists('email', $array)) {
            // check for type: email

            if(empty($array['email'])) {
                array_push($errors, 'Email field is required');
            }

            if(!filter_var($array['email'], FILTER_VALIDATE_EMAIL)) {
                // invalid address
                array_push($errors, 'That is not a Valid email address!');
            }

            $stmt = $db->prepare("SELECT email FROM accounts WHERE email = :email LIMIT 1");
            $stmt->execute(array(':email' => $array['email']));

            // is available
            if($stmt->rowCount() != 0) {
                array_push($errors, 'This email address is already associated with an account');
            }
        }

        if(array_key_exists('password', $array)) {

            // check for empty value
            if(empty($array['password'])) {
                array_push($errors, 'Password field is required');
            }

            // check for length
            if(strlen($array['password']) < 8 || strlen($array['password']) > 16 ) {
                array_push($errors, 'Password needs to be between 8 and 16 characters long');
            }

            // check if has number
            if(!preg_match("#[0-9]+#", $array['password'])) {
                array_push($errors, 'Password must include at least one number');
            }

            // check if has letter
            if(!preg_match("#[a-zA-Z]+#", $array['password'])) {
                array_push($errors, 'Password must include at least one letter');
            }
        }

        if(array_key_exists('username', $array)) {

            // check for empty value
            if(empty($array['username'])) {
                array_push($errors, 'the Username field is required');
            }
            // check for length
            if(strlen($array['username']) < 3 || strlen($array['username'] > 10)) {
                array_push($errors, 'Username has to be between 3 and 10 characters long.');
            }

            $stmt = $db->prepare("SELECT username FROM accounts WHERE username = :username LIMIT 1");
            $stmt->execute(array(':username' => $array['username']));

            // is available
            if($stmt->rowCount() != 0) {
                array_push($errors, 'Username has already been taken');
            }
        }

        // if no errors in array, then return true.
        if(count($errors) == 0) {
            return true;
        } else {
            foreach($errors as $error) {
                Notifier::add('danger', $error);
            }
        }

        $db = null;
    }

}