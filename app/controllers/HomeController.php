<?php

use TheWall\Core\Helpers;

class HomeController extends Controller {
    function getIndex() {
        // Render the view.
        $this->view->render('home/index');

    }

}