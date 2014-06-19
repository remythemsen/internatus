<?php

use TheWall\Core\Helpers;

class UserController extends Controller {

    function postLogin() {

        // check for cookie login persistence

        if(isset($_POST['persist'])) {
            $persist = ((int)$_POST['persist'] === 1 ? true : false);
        } else {
            $persist = false;
        }

        $email = (isset($_POST['email']) ? trim($_POST['email']) : '');
        $password = (isset($_POST['password']) ? trim($_POST['password']) : '');

        // Login with auth

        if(!Helpers\Auth::attempt($email, $password, $persist)) {
            // else, set notification and return to login
            Helpers\Notifier::add('warning', "We couldn't log you in with what you just entered. Please try again.");
        }
        Helpers\URL::redirect('home');
    }

    function postDelete() {

        $id = (isset($_POST['id']) ? $_POST['id'] : false);

        if(Helpers\Auth::isAdmin() && Helpers\Session::get('csrftoken') === $_POST['csrftoken']) {

            $user = UserQuery::create()->findOneById($id);

            $user->delete();

            Helpers\Observer::log('deleted_accounts', array(
                'id' => $user->getId(),
                'deletedBy' => Helpers\Session::get('user_id')
            ));

            if((int)$id === (int)Helpers\Session::get('user_id')) {
                Helpers\Auth::logout();
                Helpers\URL::redirect('home');
            } else {
                Helpers\Notifier::add('success', 'User, and related posts and comments where deleted from db');
                Helpers\URL::redirect('user/users');
            }

        } else {
            Helpers\URL::redirect('error');
        }
    }
    function postCreate() {

        // get + trim vars
        $email = (isset($_POST['email']) ? trim(Helpers\Sanitizor::escapeHTML($_POST['email'])) : false);
        $username = (isset($_POST['username']) ? trim(Helpers\Sanitizor::escapeHTML($_POST['username'])) : false);
        $password = (isset($_POST['password']) ? trim($_POST['password']) : false);

        // Validation

        if(Helpers\Validator::check(array(
            'email' => $email,
            'password' => $password,
            'username' => $username
        ))) {

            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword(Helpers\Hash::make($password));
            $user->setRole('default');


            // Persist user.
            if($user->save()) {
                Helpers\Observer::log('created_accounts', array(
                   'userId' => $user->getId(),
                   'createdWithEmail' => $user->getEmail()
                ));

                Helpers\Notifier::add('success', 'Congratulations, your user has been created, now login with your new credentials.');
            } else {
                Helpers\Notifier::add('danger', 'Something went wrong while trying to create your account. :(');
            }

        }

        Helpers\URL::redirect('home');
    }

    function getLogout() {
        Helpers\Auth::logout();
    }
    function getUsers() {
        if(Helpers\Auth::check() && Helpers\Auth::isAdmin()) {
            $this->view->users = UserQuery::create()->find();
            $this->view->render('user/users');
        } else {
            Helpers\URL::redirect('error');
        }
    }
    function getSettings() {
        if(Helpers\Auth::check()) {
            $this->view->user = UserQuery::create()->findOneById(Helpers\Session::get('user_id'));
            $this->view->render('user/settings');
        } else {
            Helpers\URL::redirect('error');
        }

    }
    function postSettings() {

        // if the user is logged in.
        if(Helpers\Auth::check()) {

            $username = (isset($_POST['username']) ? trim(Helpers\Sanitizor::escapeHTML($_POST['username'])) : false);
            $email = (isset($_POST['email']) ? trim(Helpers\Sanitizor::escapeHTML($_POST['email'])) : false);
            $user = UserQuery::create()->findOneById(Helpers\Session::get('user_id'));


            // if password isset!
            if(isset($_POST['new_password']) && ((string)$_POST['new_password'] !== '')) {

                $newPassword = (isset($_POST['new_password']) ? trim($_POST['new_password']) : false);
                $oldPassword = (isset($_POST['old_password']) ? trim($_POST['old_password']) : false);

                // check if old pass is correct.
                if(Helpers\Hash::make((string)$oldPassword) === $user->getPassword()) {
                    // validate new pass
                    if(Helpers\Validator::check(array('password', $newPassword))) {
                        // set the password
                        $user->setPassword(Helpers\Hash::make($newPassword));
                    } else {
                        Helpers\URL::redirect('user/settings');
                    }
                } else {
                    // old password did not match record
                    Helpers\Notifier::add('warning', 'Old password is wrong');
                    Helpers\URL::redirect('user/settings');
                }
            }

            // if username is new
            if((string)$username !== (string)$user->getUsername()) {
                if(Helpers\Validator::check(array('username', $username))) {
                    $user->setUsername($username);
                } else {
                    Helpers\URL::redirect('user/settings');
                }
            }

            // if email has changed (is new)
            if((string)$email !== (string)$user->getEmail()) {
                if(Helpers\Validator::check(array('email', $email))) {
                    $user->setEmail($email);
                } else {
                    Helpers\URL::redirect('user/settings');
                }
            }


            // save settings
            if($user->save()) {
                Helpers\Observer::log('updated_accounts', array(
                   'userId' => $user->getId()
                ));
                Helpers\Notifier::add('success', 'Settings was saved');
            } else {
                Helpers\Notifier::add('warning', 'Nothing was changed');
            }
        }

        // return to settings page.
        Helpers\URL::redirect('user/settings');


    }
} 