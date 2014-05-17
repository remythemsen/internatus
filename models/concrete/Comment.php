<?php 

class Comment extends Model {
    // base fields, (table columns)
    private $id, $user_id, $room_id, $comment, $post_date;

    public function get_id() {
        return $this->id;
    }
    public function get_user_id() {
        return $this->user_id;
    }
    public function get_room_id() {
        return $this->room_id;
    }
    public function get_comment() {
        return $this->comment;
    }
    public function get_post_date() {
        return $this->post_date;
    }
    public function get_username() {
        // connect and retrieve username
        $stmt = $this->db->prepare("
            SELECT users.username FROM users WHERE users.id = :user_id
            ");

        $stmt->execute(array(":user_id" => $this->user_id));

        return $stmt->fetchColumn();
    }

}

?>
