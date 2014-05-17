<?php 

class Room extends Model {
    // base fields, (table columns)
    public $id, $name, $description, $booking_count, $status;

    // arrays
    
    public $bookings, $comments;

    // getters

    public function get_id() {
        return $this->id;
    }
    public function get_name() {
        return $this->name;
    }
    public function get_description() {
        return $this->description;
    }
    public function get_booking_count() {
        return $this->booking_count;
    }
    public function get_status() {
        return $this->status;
    }
    public function is_available() {
        $stmt = $this->db->prepare("SELECT IF( (SELECT COUNT(*) FROM bookings WHERE bookings.room_id = rooms.id AND NOW() BETWEEN bookings.time_from AND bookings.time_to) > 0, 0, 1 ) AS available FROM rooms WHERE id = :room_id");

        $stmt->execute(array(":room_id" => $this->id));

        return $stmt->fetchColumn();
    }

    // to use when populating or repopulating an instance of room
    public function get_room_by_id($room_id) {

        // statement prepare
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = :room_id");
        
        // setting fetchmode to retrieve data into current object.
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);

        // executing statement
        $stmt->execute(array(':room_id' => $room_id));
        
        // fetching data into this object.
        $stmt->fetch();

        // getting comments
        $this->comments = $this->get_comments();

        // getting bookings
        $this->bookings = $this->get_bookings();
    }

    public function get_comments_count() {
        
        $stmt = $this->db->prepare("CALL count_comments(:room_id);");

        $stmt->execute(array(":room_id" => $this->id));

        return $stmt->fetchColumn();
        
    }

    private function get_comments() {
        // get comments from db, then return
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE comments.room_id = :room_id ORDER BY post_date DESC");
        // set fetch mode to class
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Comment");
        // execute
        $stmt->execute(array(":room_id" => $this->id));
        //r return
        return $stmt->fetchAll();
    }

    private function get_bookings() {
        // get bookings from db, then return
        $stmt = $this->db->prepare("SELECT * FROM bookings WHERE bookings.room_id = :room_id");
        // set fetch mode to class
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Booking");

        // execute
        $stmt->execute(array(":room_id" => $this->id));
        //r return
        return $stmt->fetchAll();
    }
}

?>
