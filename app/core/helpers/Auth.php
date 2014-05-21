<?php

class Auth {

    public static function attempt($username, $password) {

        $db = new Database();

        try {
            $stmt = $db->prepare('SELECT * FROM accounts WHERE username = :username AND password = :password');
            $stmt->execute(array(':username' => $username, ':password' => Hash::make($password)));
        } catch (PDOException $e) {
            throw $e;
        }

        $count = $stmt->rowCount();
        $account_id = $stmt->fetchColumn(0);

        // closing connection
        $db = null;

        if($count==1) {
            Auth::login($account_id);
            return true;
        }
    }

    public static function check() {
        if(Session::get('account_id') != null) {
            return true;
        }
    }

    public static function logout() {
        Session::set('account_id', null);
        Session::destroy();
        header('location: '.BASE_URL);
    }

    public static function account($property) {

        $db = new Database();

        try {
            $stmt = $db->prepare('SELECT * FROM accounts WHERE id = :id');
            $stmt->execute(array(':id' => Session::get('account_id')));
        } catch (PDOException $e) {
            throw $e;
        }

        $result = $stmt->fetch();

        $db = null;

        return $result[$property];
    }

    private static function login($account_id) {
        // Setting the current account's id in the session.
        Session::set('account_id', $account_id);
    }

} 