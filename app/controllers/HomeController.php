<?php namespace Internatus\Controllers;

use Internatus\Core\Helpers;
use Internatus\Core\Base;

class HomeController extends Base\Controller {
    function getIndex() {
        // Render the view.
        $this->view->render('home/index');

    }
}