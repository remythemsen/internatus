<?php 

class RoomsViewModel extends ViewModel {
    
    // order by, (sql passed as string), xhr = if json encoding).
    public function get_rooms($order_by = false, $xhr = false) {

        if(isset($order_by)) {
            $order = $order_by;
        } else {
            $order = '';
        }
        
        $statement = $this->db->prepare("
             SELECT * FROM rooms WHERE status != 0
            {$order}
        ");

        $statement->setFetchMode(PDO::FETCH_CLASS, "Room");
        
        $statement->execute();

        $rooms = $statement->fetchAll();


        // if xhr != false, then echo & json encode result.
        if($xhr) {
            echo json_encode($rooms);
        } else {
            return $rooms;
        }
    }

    public function create_comment() {
        $comment = $_POST['comment_field'];
        $room_id = $_POST['room_id'];
        $user_id = Session::get('user_id');

        $stmt = $this->db->prepare("
            INSERT INTO comments (comment, user_id, room_id) 
            VALUES (?, ?, ?)
        ");
        
        $result = $stmt->execute(array($comment, $user_id, $room_id));
        
        return $result;
    }
}

?>
