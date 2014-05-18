<?php 

class AccountController extends Controller {
    function index() {

        // check whether the account is logged in, and redirecting.

        if(isset($this->account)) {
            URL::redirect('home');
        } else {
            URL::redirect('account/login');
        }   
    }

    function login() {
        $this->view->pagetitle = $this->registry->config->general->pagetitle;
        $this->view->render('account/login');
    }


    // post login form 
    function login_do() {

        // Run login function from model object.
        //$result = $this->model->login();

        $username = (isset($_POST['username']) ? $_POST['username'] : '');
        $password = (isset($_POST['password']) ? $_POST['password'] : '');

        // Login with auth

        if(Auth::attempt($username, $password)) {
            URL::redirect('home');
        } else {
            // else, set notification and return to login 
            $this->addNotification('warning', "We couldn't log you in with what you just entered. Please try again.");
            URL::redirect('account/login');
        }
    }

    function create_do() {
        if(!Auth::check()) {
            $this->model->create();
            URL::redirect('account/login');
            $this->addNotification('success', 'Please log in with your new credentials');
        } else {
            URL::redirect('home');
        }
    }

    function logout() {
        Auth::logout();
    }

}