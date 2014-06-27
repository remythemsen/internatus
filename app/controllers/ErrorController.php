<?php namespace Internatus\Controllers;

use Internatus\Core\Base;

class ErrorController extends Base\Controller {

    public function getIndex() {

        $this->view->render('error/not_found');

    }

}
