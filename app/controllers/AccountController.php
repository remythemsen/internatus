<?php 

class AccountController extends Controller {
    function index() {

        // check whether the account is logged in, and redirecting.

        if(isset($this->account)) {
            $this->redirect_to('home');
        } else {
            $this->redirect_to('account/login');
        }   
    }

    function login() {
        $this->view->pagetitle = $this->registry->config->general->pagetitle;
        $this->view->render('account/login');
    }
    
    // post login form 
    function login_do() {

        // Run login function from model object.
        $result = $this->model->login();
        
        // if successful, change header to dashboard.
        if($result) {
            $this->redirect_to('home');
        } else {
            // else, set notification and return to login 
            $this->addNotification('warning', "We couldn't log you in with what you just entered. Please try again.");
            $this->redirect_to('referer');
        }
    }
    function settings() {
         if(isset($this->account)) {
            $this->view->render('account/settings');
        } else {
            $this->redirect_to('account/login');
        }
    }




    function create_room_do() {
        $result = $this->model->create_room();
        if($result) {
            $this->addNotification('success', 'The Room was Successfully Created');
            $this->redirect_to('account/settings');
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
            $this->redirect_to('account/create');
        } else if($result) {
            $this->addNotification('success', 'Your Account was successfully created, login to proceed');
            $this->redirect_to('account');     
        } else {
            $this->redirect_to('account/create');
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
            $this->redirect_to('referer');

        } else {
            
            $this->redirect_to('account/settings');
            $this->addNotification('success', 'Your '.$setting.' was changed successfully');
        }
    }
    function xhr_update_user() {
        $result = $this->model->update_user();
    }

 
    function logout() {
        Session::set('account_id', null);
        Session::destroy();
        $this->account = null;
        header('location: '.BASE_URL);
    }
    function change_site_name() {
        
        $result = $this->model->change_site_name();
        if($result) {
            $this->addNotification('success', 'The name of the site was changed Successfully');
            $this->redirect_to('account');
        } else {
            $this->addNotification('warning', 'Something went wrong!');
        }

    }
}
?>
