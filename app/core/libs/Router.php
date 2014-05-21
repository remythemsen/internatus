<?php
class Router {


    // the path to controllers.
    private $path;
    
    // the controller filepath
    public $file;
    
    // the controller name
    public $controller;
    
    // the passed action ('controller/action').
    public $action;

    // the passed parameter action/param
    public $param;


    
    // Set the path to the controller directory.
    function setPath($path) {

        // does the path direct to a valid dir?
        if(is_dir($path) == false) {
            throw new Exception ('The controllers path: ('.$path.') was not found, or is not accessible');
        }

        // setting the path in the object.
        $this->path = $path;
    }
    
    // Get the controller file from the url.
    private function getController() {

                
        $route = (empty($_GET['url'])) ? '' : $_GET['url'];

        // checking for config file.
        if(!file_exists(__SITE_PATH."app/config/config.xml")) {

            // checks for logged in account, and kills it
            if(isset($_SESSION['account_id'])) {
                Session::destroy();
            }

            // setting header to install route
            
            $installRouteParts = explode('/', $route);
            
            if($installRouteParts[0] != 'install') {
                header("Location: ".BASE_URL."install");
            }
        }
        
        //setting default controller name if none was specified.
        if (empty($route))
        {
                $route = 'home';
        }
        else
        {
            // splitting the url up into parts
            $parts = explode('/', $route);
            $this->controller = $parts[0];
            if(isset( $parts[1]))
            {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->action = 'post'.$parts[1];
                } else {
                    $this->action = 'get'.$parts[1];
                }

                // checking for parameter
                if(isset( $parts[2])) {
                    $this->param = $parts[2];
                }
            }
        }

        if (empty($this->controller))
        {
                $this->controller = 'home';
        }

        // get action, or set index action as default.
        if (empty($this->action))
        {
                $this->action = 'getIndex';
        }

        // finally, setting the filepath to the controller.
        $this->file = $this->path .'/'. ucfirst($this->controller) . 'Controller.php';
    }

    public function loader() {
        
        
        // getting the controller
        $this->getController();

        // if the file is not ok, die, and throw error.
        // TODO: handle with error controller.
        if (is_readable($this->file) == false)
        {
            header("Location: ".BASE_URL."error/not_found");
        }

        // including the necessary file.
        include $this->file;

        // make a new instance of the controller.
        $class = $this->controller.'Controller';

        // new controller
        $controller = new $class();


        // if no callable action, then call the default ('index').
        // (the index action is mandatory in controller classes).
        if (is_callable(array($controller, $this->action)) == false)
        {
                $action = 'getIndex';
        }
        else
        {
                // setting the action
                $action = $this->action;
        }

        // if param is passed in url, 
        if(!empty($this->param)) {
            $controller->$action($this->param);
        } else {
        
            // Run the action!
            $controller->$action();
        }
    }
   
}
