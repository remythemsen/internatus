<?php namespace Internatus\Core\Helpers;

use Internatus\Domain\User;
use Internatus\Domain\PersistedSession;

class Auth {

    public static function attempt($email, $password, $persist) {

        $user = User\UserQuery::create()
            ->filterByEmail($email)
            ->findOne();
        if($user) {
            if($user->getPassword() === Hash::make($password)) {
                Auth::login($user->getId(), $persist);
                return true;
            }
        }
    }

    public static function check($redirect = false) {
        if(Session::get('user_id') != null) {
            return true;
        }

        // redirecting if specified.
        if((bool)$redirect === true) {
            if((string)$redirect !== '') {
                URL::redirect($redirect);
            } else {
                URL::redirect('error');
            }
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
            PersistedSession\PersistedSessionQuery::create()->findOneByUserId(Session::get('user_id'))->delete();
        }

        // Reset session data, and destroy.
        Session::set('user_id', null);
        Session::destroy();
        header('location: '.BASE_URL);
    }

    public static function user() {
        $user = User\UserQuery::create()->findPk(Session::get('user_id'));
        return $user;
    }

    public static function isAdmin($redirect = false) {
        if(Auth::check($redirect)) {
            if((string)User\UserQuery::create()->findPk(Session::get('user_id'))->getRole() === 'administrator') {
                return true;
            }
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

            $persistedSession = PersistedSession\PersistedSessionQuery::create()->findOneByUserId((int)$user_id);

            if(count($persistedSession) > 0) {
                $persistedSession->setToken($token);
                $persistedSession->save();
            } else {
                $persistedSession = new PersistedSession\PersistedSession();
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