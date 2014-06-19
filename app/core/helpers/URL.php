<?php namespace TheWall\Core\Helpers;

class URL {
    public static function base() {



        // Get base the URL
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        } else {
            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }



        if(isset($_GET['url'])) {

            // exploding the get url
            $routeParts = explode('/', $_GET['url']);
            $urlParts = explode('/', $url);

            foreach($routeParts as $routePart) {
                array_pop($urlParts);
            }



            // gathering the parts for the base URL

            $url = implode('/', $urlParts);

            // adding the ending slash
            $url = $url.'/';

        }

        return $url;

    }
    public static function redirect($url) {
        if((string)$url === 'referer') {
            header("Location:".$_SERVER['HTTP_REFERER']);
        } else {
            header("Location:".BASE_URL.$url);
        }
    }
} 