<?php 
class Error extends Controller {

    public function index() {
        
        // render view
        $this->view->render('error/index');

    }

    public function not_found() {
        $this->addNotification('warning', 'Error: 404, Page not found');
        $this->view->render('error/not_found');
    }
}
?>
