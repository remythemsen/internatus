<?php 

class Booking extends Model {
    // base fields (from table)
    private $id, $user_id, $room_id, $time_from, $time_to;
       
    public function get_id() {
        return $this->id;
    }
    public function get_user_id() {
        return $this->user_id;
    }
    public function get_room_id() {
        return $this->room_id;
    }
    public function get_time_from() {
        return $this->time_from;
    }
    public function get_time_to() {
        return $this->time_to;
    }
    public function get_username() {
        // connect and retrieve username
        $stmt = $this->db->prepare("
            SELECT users.username FROM users WHERE users.id = :user_id
            ");

        $stmt->execute(array(":user_id" => $this->user_id));

        return $stmt->fetchColumn();
    }

    public function get_room_name() {
        // connect and retrieve room name
        $stmt = $this->db->prepare("
            SELECT rooms.name FROM rooms WHERE rooms.id = :room_id
            ");

        $stmt->execute(array(":room_id" => $this->room_id));

        return $stmt->fetchColumn();
    }
    
    
}

?>
