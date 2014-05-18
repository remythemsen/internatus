<?php 
// This is the base controller class!

Abstract Class Controller {

    protected $registry;
    protected $model;
    protected $account;

    function __construct($registry) {

        // storing the global registry.
        $this->registry = $registry;

        // get the current active controller name
        $controllerName = preg_replace('/Controller$/', '', get_class($this));

        // path to the view models
        $path = __SITE_PATH.'app/models/viewmodels/';

        // complete path with filename.
        $file = $path.$controllerName.'ViewModel.php';

        // including the needed viewmodel file.
        if(file_exists($file)) {
            include $file;

            $viewModel = $controllerName.'ViewModel';
            // storing new instance of the viewmodel in this.
            $this->model = new $viewModel($this->registry);

        }
                        
        // creating a new view object
        $this->view = new View($this->registry);
        
        // getting the account id from session variable, if any.
        $account_id = Session::get('account_id');
        
        // instantiating a new account
        if(isset($account_id)) {
            // TODO: REDO THIS SHITE! :D
            $this->account = new Account($this->registry);
            $this->account->get_account_object($account_id);
            $this->view->account = $this->account;
        }

    }
    
    // Force the index method for all controllers
    abstract function index();





    
    
}

?>
