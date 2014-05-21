<?php 

class AccountController extends Controller {
    function getIndex() {

        // check whether the account is logged in, and redirecting.

        if(Auth::check()) {
            URL::redirect('home');
        } else {
            URL::redirect('account/login');
        }   
    }

    function getLogin() {
        $this->view->render('account/login');
    }


    // post login form 
    function postLogin() {

        $username = (isset($_POST['username']) ? trim($_POST['username']) : '');
        $password = (isset($_POST['password']) ? trim($_POST['password']) : '');

        // Login with auth

        if(Auth::attempt($username, $password)) {
            URL::redirect('home');
        } else {
            // else, set notification and return to login 
            Notifier::add('warning', "We couldn't log you in with what you just entered. Please try again.");
            URL::redirect('account/login');
        }
    }

    function postCreate() {
        if(!Auth::check()) {
            // get + trim vars
            $username = (isset($_POST['username']) ? trim($_POST['username']) : false);
            $email = (isset($_POST['email']) ? trim($_POST['email']) : false);
            $password = (isset($_POST['password']) ? trim($_POST['password']) : false);

            // Validation

            if(Validator::check(array(
                'username' => $username,
                'password' => $password,
                'email' => $email
            ))) {

                $db = new Database;

                $stmt = $db->prepare("INSERT INTO accounts (username, password, email, is_admin, is_active) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute(array($username, Hash::make($password), $email, 0, 1));
                Notifier::add('success', 'Congratulations, your account has been created, now login with your new credentials.');
            }
        }

        URL::redirect('home');
    }

    function getLogout() {
        Auth::logout();
    }

}