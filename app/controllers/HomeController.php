<?php

use TheWall\Core\Helpers;

class HomeController extends Controller {
    function getIndex() {

        // Retrieve All posts and pass to view
        $this->view->posts = PostQuery::create()->orderById('desc')->find();

        /*

        MESSAGES DISABLED

        $this->view->messages = MessageQuery::create()
                                    ->filterByReceiverId(Helpers\Session::get('user_id'))
                                    ->orderById('desc')
                                    ->find();
        */
        // Render the view.
        $this->view->render('home/index');

    }

}