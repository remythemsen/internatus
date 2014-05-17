<?php

class Home extends Controller {
    function index() {
        if(!isset($this->user)) {
            $this->redirect_to('account/login');
        }
        else {
            // get popular rooms
            $this->view->rooms = $this->model->get_rooms();
            // get users bookings
            $this->view->bookings = $this->model->get_bookings();
            // render the view
            $this->view->render('home/index');
        }
    }
}

?>
