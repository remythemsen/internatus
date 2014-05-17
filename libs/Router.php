<?php
class Router {

    // the registry reference
    private $registry;

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

    function __construct($registry) {
        $this->registry = $registry;
    }
    
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
        if(!file_exists(__SITE_PATH."/config/config.xml")) {

            // checks for logged in user, and kills it
            if(isset($_SESSION['user_id'])) {
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
                $this->action = $parts[1];
                
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
                $this->action = 'index';
        }

        // finally, setting the filepath to the controller.
        $this->file = $this->path .'/'. ucfirst($this->controller) . '.php';
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
        $class = $this->controller;

        // new controller, passing in the registry.
        $controller = new $class($this->registry);


        // if no callable action, then call the default ('index').
        // (the index action is mandatory in controller classes).
        if (is_callable(array($controller, $this->action)) == false)
        {
                $action = 'Index';
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
