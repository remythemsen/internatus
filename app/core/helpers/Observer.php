<?php
namespace TheWall\Core\Helpers;

class Observer {
    public static function log($fileName, $args = array(), $ip = true) {

        $file = __SITE_PATH.'logs/'.$fileName.'.txt';

        // adding timestamp
        $data = '[time]'.date('d-m-y - H:i:s');



        foreach($args as $key => $value) {
            $data .= ' : ['.$key.']'.$value;
        }

        if($ip) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $data .= ' : IP['.$ip.']';
        }

        // new line
        $data .= "\n";

        if (!is_dir(__SITE_PATH.'logs')) {
            mkdir(__SITE_PATH.'logs', 0755, true);
        }

        file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
    }

}