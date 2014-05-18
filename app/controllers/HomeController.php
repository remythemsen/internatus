<?php

class HomeController extends Controller {
    function index() {
        if(!isset($this->account)) {
            $this->redirect_to('account/login');
        }
        else {
            // render the view
            $this->view->render('home/index');
        }
    }
}