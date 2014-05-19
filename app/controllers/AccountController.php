<?php 

class AccountController extends Controller {
    function index() {

        // check whether the account is logged in, and redirecting.

        if(Auth::check()) {
            URL::redirect('home');
        } else {
            URL::redirect('account/login');
        }   
    }

    function login() {
        $this->view->render('account/login');
    }


    // post login form 
    function login_do() {

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

    function create_do() {
        if(!Auth::check()) {
            if($this->model->create()) {
                URL::redirect('account/login');
            } else {
                URL::redirect('account/login');
            }

        } else {
            URL::redirect('home');
        }
    }

    function logout() {
        Auth::logout();
    }

}