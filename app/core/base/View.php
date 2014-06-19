<?php

use TheWall\Core\Helpers\Config;
use TheWall\Core\Helpers;

class View {


    // an array of javascript file names for the specific view.
    public $js = array();
    public $pagetitle;
    protected $js_config;
    
    function __construct() {

        // add view specific js files.
        $this->add_js();
        $this->js_config = array('BASE_URL' => BASE_URL, 'csrftoken' => Helpers\Session::get('csrftoken'));
        $this->pagetitle = Config::get()->general->pagetitle;
        
    }
    public function render($name, $template = true) {

        if($template === false) {
            require __SITE_PATH.'app/views/' . $name . '.php';
        } else {

            require __SITE_PATH.'app/views/header.php';
            require __SITE_PATH.'app/views/' . $name . '.php';
            require __SITE_PATH.'app/views/footer.php';


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
        $directory = __SITE_PATH.'public/js/views/'.$url[0];

        // check for a js dir in the view folder.
        if (file_exists($directory) && is_dir($directory)) {
                        
            // find js files in current view folder
            $files = scandir($directory);

            // push files to js array 
            foreach($files as $file) {
                $file_parts = pathinfo($file);
                $file_extension = $file_parts['extension'];
                if((string)$file_extension === 'js') {
                    array_push($this->js, BASE_URL.'js/views/'.$url[0].'/'.$file);
                }
            }
        } 
    }


}
