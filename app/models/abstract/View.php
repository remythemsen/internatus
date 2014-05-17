<?php
// this is the base view class

class View {

    // an array of javascript file names for the specific view.
    public $js = array();
    protected $registry;
    protected $js_config;
    
    function __construct($registry) {
        // add view specific js files.
        $this->add_js();
        $this->registry = $registry;
        $this->js_config = array('BASE_URL' => BASE_URL);
        
    }
    public function render($name, $template = true) {
        if($template == false) {
            require __SITE_PATH.'views/' . $name . '.php';
        } else {
            require __SITE_PATH.'views/header.php';
            require __SITE_PATH.'views/' . $name . '.php';
            require __SITE_PATH.'views/footer.php';
        }
    }

    public function add_js() {
        // Getting the URL, splitting it up in array
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        if(empty($url[0])) {
            $url[0] = 'index';
        }
        
        // find current view's directory
        $directory = 'views/'.$url[0].'/js';

        // check for a js dir in the view folder.
        if (file_exists($directory) && is_dir($directory)) {
                        
            // find js files in current view folder
            $files = scandir($directory);

            // push files to js array 
            foreach($files as $file) {
                $file_parts = pathinfo($file);
                $file_extension = $file_parts['extension'];
                if($file_extension == 'js') {
                    array_push($this->js, BASE_URL.$directory.'/'.$file);
                }
            }
        } 
    }
    protected function printNotifications() {

        if(isset($_SESSION['notifications']) && !empty($_SESSION['notifications']) && is_array($_SESSION['notifications'])) {
            foreach($_SESSION['notifications'] as $notification) {
                echo '<div class="alert '.$notification['type'].'">'.$notification['message'].'<i class="icon-cancel-circled remove-notification" style="float:right;"></i></div>';
            }
            // unsetting the global variable.
            unset($_SESSION['notifications']);
        }
    }

}
    
?>
