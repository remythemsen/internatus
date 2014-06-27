<?php namespace Internatus\Controllers;

use Internatus\Core\Base;
use Internatus\Core\Helpers;

class DashBoardController extends Base\Controller {
    function getIndex() {
        if(Helpers\Auth::check('user/login')) {
            $this->view->render('dashboard/index');
        }
    }
}