<?php namespace TheWall\Core\Helpers;

class Auth {

    public static function attempt($email, $password, $persist) {

        $user = \UserQuery::create()
            ->filterByEmail($email)
            ->findOne();
        if($user) {
            if($user->getPassword() === Hash::make($password)) {
                Auth::login($user->getId(), $persist);
                return true;
            }
        }
    }

    public static function check() {
        if(Session::get('user_id') != null) {
            return true;
        }
    }

    public static function logout() {

        // Remove cookie
        if(isset($_COOKIE['persisted_session'])) {
            unset($_COOKIE['persisted_session']);
            // setting cookie timeout to 'one hour ago'.

            // Checking for SSL connection
            $ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? true : NULL);

            setcookie('persisted_session', '', time() - 3600, '/', NULL, $ssl, True);

            // check for existing row in database

            // Remove DB token entry
            \PersistedSessionQuery::create()->findOneByUserId(Session::get('user_id'))->delete();
        }

        // Reset session data, and destroy.
        Session::set('user_id', null);
        Session::destroy();
        header('location: '.BASE_URL);
    }

    public static function user() {
        $user = \UserQuery::create()->findPk(Session::get('user_id'));
        return $user;
    }

    public static function isAdmin() {
        if((string)\UserQuery::create()->findPk(Session::get('user_id'))->getRole() === 'administrator') {
            return true;
        }
    }

    private static function login($user_id, $persist) {

        // regenerating session id, to kick off previous potential session hijackers
        Session::regenerate();
        // Setting the current user's id in the session.
        Session::set('user_id', $user_id);
        Session::set('csrftoken', SHA1($user_id.Config::get()->general->salt));

        if((bool)$persist === true) {

            /*
             | Creating Cookie, and Database table row for Session persistance.
             */

            // generate Random token
            // TODO: Find another way to generate token
            $token = bin2hex(openssl_random_pseudo_bytes(16));

            // insert token into, or update PersistedSessions table along with userid

            $persistedSession = \PersistedSessionQuery::create()->findOneByUserId((int)$user_id);

            if(count($persistedSession) > 0) {
                $persistedSession->setToken($token);
                $persistedSession->save();
            } else {
                $persistedSession = new \PersistedSession();
                $persistedSession->setUserId($user_id);
                $persistedSession->setToken($token);
                $persistedSession->save();
            }

            // Time for persistance: (30 days)
            $time = time()+2592000;

            // creating cookie
            $cookie = $user_id . ':' . $token;
            $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
            $cookie .= ':' . $mac;

            // Checking for SSL connection
            $ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? true : false);
            setcookie('persisted_session', $cookie, $time, '/', NULL, $ssl, True);

        }


    }

} 