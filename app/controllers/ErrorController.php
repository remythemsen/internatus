<?php 
class ErrorController extends Controller {

    public function getIndex() {
        
        // render view
        $this->view->render('error/index');

    }

    public function not_found() {
        Notifier::add('warning', 'Error: 404, Page not found');
        $this->view->render('error/not_found');
    }
}
?>
