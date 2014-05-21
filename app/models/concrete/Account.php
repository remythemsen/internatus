<?php 

class Account extends Model {
    private $id, $username, $password, $is_admin, $email;

    public function get_id() {
        return $this->id;
    }

    public function get_username() {
        return $this->username;
    }
    public function is_admin() {
        return $this->is_admin;
    }
    public function get_email() {
        return $this->email;
    }
    public function can_book() {
        return $this->can_book;
    }

    public function get_account_object($account_id) {
        // prepare statement
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE id = :id");
                
        // setting fetchmode to return associative array
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // executing statement
        $stmt->execute(array(':id' => $account_id));

        $result = $stmt->fetch();

        $this->username = $result['username'];
        $this->is_admin = $result['is_admin'];
        $this->email = $result['email'];
        $this->can_book = $result['can_book'];
    }
}