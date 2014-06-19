<?php

use TheWall\Core\Helpers;

class DashBoardController extends Controller {
    function getIndex() {
        if(Helpers\Auth::check('user/login')) {
            $this->view->render('dashboard/index');
        }
    }
}