<?php 

class AccountController extends Controller {
    function index() {

        // check whether the account is logged in, and redirecting.

        if(isset($this->account)) {
            $this->redirect_to('home');
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
            URL::redirect('referer');
        }
    }
    function settings() {
         if(isset($this->account)) {
            $this->view->render('account/settings');
        } else {
            URL::redirect('account/login');
        }
    }

    function xhr_get_users() {
        $this->model->get_users();
    }
    function create() {
        if(isset($this->account)) {
            header("location: ".BASE_URL."account");
        } else {
            $this->view->render('account/create');
        }
    }

    function create_do() {
        $result = $this->model->create();
        if(is_string($result)) {
            $this->addNotification('warning', $result);
            URL::redirect('account/create');
        } else if($result) {
            $this->addNotification('success', 'Your Account was successfully created, login to proceed');
            URL::redirect('account');
        } else {
            URL::redirect('account/create');
        }
    }

    function update($setting) {
        $this->view->setting = $setting;
        $this->view->render('account/update');
    }
    
    function update_do($setting) {

        $result = $this->model->update($setting);

        if(!$result) {
            $this->addNotification('warning', 'That Username has already been taken');
            URL::redirect('referer');

        } else {
            
            URL::redirect('account/settings');
            $this->addNotification('success', 'Your '.$setting.' was changed successfully');
        }
    }
    function xhr_update_user() {
        $result = $this->model->update_user();
    }

 
    function logout() {
        Auth::logout();
    }
    function change_site_name() {
        
        $result = $this->model->change_site_name();
        if($result) {
            $this->addNotification('success', 'The name of the site was changed Successfully');
            URL::redirect('account');
        } else {
            $this->addNotification('warning', 'Something went wrong!');
        }

    }
}
?>
