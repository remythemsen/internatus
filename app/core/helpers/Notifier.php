<?php namespace TheWall\Core\Helpers;

class Notifier {
    // adds a notification array to the global array 'notifications'.
    public static function add($type, $message) {
        if(!isset($_SESSION['notifications'])) {
            $_SESSION['notifications'] = array();
        }
        // use 'danger', 'warning', 'success', 'info', 'primary', 'secondary', 'default'.
        $notification = array('type' => $type, 'message' => $message);
        array_push($_SESSION['notifications'], $notification);
    }
    public static function printAll() {
        if(isset($_SESSION['notifications']) && !empty($_SESSION['notifications']) && is_array($_SESSION['notifications'])) {
            foreach($_SESSION['notifications'] as $notification) {
                echo '<div class="alert '.$notification['type'].'">'.$notification['message'].'<i class="icon-cancel-circled remove-notification" style="float:right;"></i></div>';
            }
            // unsetting the global variable.
            unset($_SESSION['notifications']);
        }
    }
}