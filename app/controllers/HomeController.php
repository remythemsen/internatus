<?php

class HomeController extends Controller {
    function index() {
        if(Auth::check()) {
            // render the view
            $this->view->render('home/index');
        }
        else {
            URL::redirect('account/login');
        }
    }
}