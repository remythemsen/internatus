<?php namespace TheWall\Core\Helpers;

use UserQuery;

class Validator {
    public static function check($array) {

        // Accepts Associate Array, keys: password, email.

        $errors = array();

        if(array_key_exists('text', $array)) {
            // check for empty string
            if((string)$array['text'] === '') {
                array_push($errors, 'That was an empty Post, you should write something more interesting!');
            }
        }

        if(array_key_exists('email', $array)) {
            // check for type: email

            if(empty($array['email'])) {
                array_push($errors, 'Email field is required');
            }

            if(!filter_var($array['email'], FILTER_VALIDATE_EMAIL)) {
                // invalid address
                array_push($errors, 'That is not a Valid email address!');
            }

            $result = UserQuery::create()->findOneByEmail($array['email']);

            // is available
            if($result) {
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

        if(array_key_exists('csrftoken', $array)) {
            // check for token match
            if(!(string)$array['csrftoken'] === Session::get('csrftoken')) {
                array_push($errors, 'CSRF Token mismatch');
            }
        }

        if(array_key_exists('username', $array)) {

            // check for empty value
            if(empty($array['username'])) {
                array_push($errors, 'Username field is required');
            }

            // check for length
            if(strlen($array['username']) < 3 || strlen($array['username']) > 16 ) {
                array_push($errors, 'Username needs to be between 8 and 16 characters long');
            }

            // check if has number
            if(preg_match("#[0-9]+#", $array['username'])) {
                array_push($errors, 'usernames must not include numbers');
            }

            // check if has special characters
            if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $array['username'])) {
                array_push($errors, 'usernames must not include special characters');
            }

            $result = UserQuery::create()->findOneByUsername($array['username']);

            // is available
            if($result) {
                array_push($errors, 'This username is already associated with an account');
            }
        }


        // if no errors in array, then return true.
        if((int)count($errors) === 0) {
            return true;
        } else {
            foreach($errors as $error) {
                Notifier::add('danger', $error);
            }
        }

        $db = null;
    }

}