<?php 

class AccountViewModel extends ViewModel {
    
    
    public function login() {

        $result = false;

        // getting post variables from form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // authenticating credentials, returning the user id.
        $user_id = $this->authenticate($username, $password);

        if($user_id != null) {
            // Setting the current user's id in the session.
            Session::set('user_id', $user_id);
            $result = true;
        }

        return $result;
    }

    public function authenticate($username, $password) {
        
        // prepared statement
        $statement = $this->db->prepare("SELECT * FROM users WHERE username = :username AND password = MD5(:password)"); 
       

        // setting fetchmode to return as class
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        // execute statement
        $statement->execute(array(
            ':username' => $username,
            ':password' => $password
        ));
        
        // counting matches
        $count = $statement->rowCount();

        if($count>0) {
            
            $user = $statement->fetch();

            if($user['is_active'] > 0) {
             
                return $user['id'];
            }
        }
    }
    public function create() {

        $result = false;
        
        $username = (isset($_POST['username']) ? trim($_POST['username']) : false);
        $email = (isset($_POST['email']) ? trim($_POST['email']) : false);
        $password = (isset($_POST['password']) ? MD5(trim($_POST['password'])) : false);
        
        // checking for all required data
        if($username && $email && $password) {

            // check is username already exists:
            $statement = $this->db->prepare("SELECT username FROM users WHERE username = ? LIMIT 1");
            // execute
            $statement->execute(array($username));
            if ( $statement->rowCount() == 0 ) {
                // prepared statement
                $statement = $this->db->prepare("INSERT INTO users (username, password, email, is_admin, is_active) VALUES (?, ?, ?, ?, ?)"); 
           
                // execute statement
                $statement->execute(array($username, $password, $email, 0, 1));

                $result = true;
                
            } else {
                $result = 'Username is already taken';
            }
        } else {
            $result = 'Some fields are not filled out!';
        }
        // returning the result to controller
        return $result;
    }



    public function get_rooms() {

        
        $statement = $this->db->prepare("
            SELECT rooms.name, rooms.booking_count, rooms.status, rooms.id, (
                SELECT COUNT(*) 
                FROM comments 
                WHERE comments.room_id = rooms.id
            ) 
            AS comments 
            FROM rooms
        ");

        
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
        $statement->execute();

        

        $records = $statement->fetchAll();

        echo json_encode($records);
    }

    public function get_room() {
        
        $room_id = $_GET['room_id'];
        
        $stmt = $this->db->prepare('SELECT * FROM rooms WHERE id = :room_id');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute(array(':room_id' => $room_id));
        $record = $stmt->fetchAll();

        // getting bookings for the room.
        $stmt = $this->db->prepare('SELECT bookings.id, users.username, users.email, bookings.time_from, bookings.time_to
                                FROM bookings
                                INNER JOIN users
                                ON bookings.user_id = users.id
                                WHERE room_id = :room_id'
                            );
        //$stmt = $this->db->prepare('SELECT * FROM bookings WHERE room_id = :room_id');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute(array(':room_id' => $room_id));
        $bookings = $stmt->fetchAll();

        // getting comments for the room.
        $stmt = $this->db->prepare('SELECT comments.comment, comments.id, comments.post_date, users.username
            FROM comments 
            INNER JOIN users 
            ON users.id = user_id
            WHERE room_id = :room_id');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute(array(':room_id' => $room_id));
        $comments = $stmt->fetchAll();

        
        // adding bookings to the room object.

        $record['bookings'] = $bookings;
        $record['comments'] = $comments;

        echo json_encode($record);
    }

    public function create_room() {

        $name = $_POST['name'];
        $name = trim($name);

        $description = (isset($_POST['description']) ? $_POST['description'] : '');

        // prepared statement
        $statement = $this->db->prepare("INSERT INTO rooms (name, description) VALUES (?, ?)");
       
        // execute statement
        $result = $statement->execute(array($name, $description));

        return $result;
    }

    public function delete_room() {
        $id = $_POST['id'];
        $statement = $this->db->prepare('DELETE FROM rooms WHERE id = "'.$id.'"');
        $statement->execute();
    }    

    public function delete_comment() {
        $id = $_POST['id'];
        $stmt = $this->db->prepare('DELETE FROM comments WHERE id = "'.$id.'"');
        $stmt->execute();
    }
    public function get_users() {
        
        $statement = $this->db->prepare('SELECT id, username, is_admin, is_active, can_book FROM users');

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
        $statement->execute();

        $records = $statement->fetchAll();

        echo json_encode($records);
    }
    public function update($setting) {
        // input
        $update_input = $_POST['update_input'];
        // need user id
        $user_id = Session::get('user_id');
        // setting (columns)
        $column = $setting;
        
        // setting MD5 encryption on password string.
        if($column == 'password') {
            $update_input = MD5($update_input);
        }

        if($column == 'username') {
            // check is username already exists:
            $statement = $this->db->prepare("SELECT username FROM users WHERE username = ? LIMIT 1");
            // execute
            $statement->execute(array($update_input));
            if ( $statement->rowCount() == 0 ) {
                // updating record in db
                $stmt = $this->db->prepare("UPDATE users SET {$column} = :user_input WHERE id = :user_id");
                $result = $stmt->execute(array(
                ':user_input' => $update_input,
                ':user_id' => $user_id
                ));
                return true;
            } else {
                return false;
            }
        } else {
        
            // updating record in db
            $stmt = $this->db->prepare("UPDATE users SET {$column} = :user_input WHERE id = :user_id");
            $result = $stmt->execute(array(
                ':user_input' => $update_input,
                ':user_id' => $user_id
            ));

            return true;
        }
        
    }
    // ajax call to update the users from admin menu
    public function update_user() {

        $result = false;
        
        $user_id = $_POST['id'];
        $column = $_POST['setting'];
        $action = $_POST['action'];

        if($column == 'is_admin') {
            if($action == 'Demote') {
                $update_input = 0;
            } else {
                $update_input = 1;
            }
        }

        if($column == 'is_active') {
            if($action == 'Activate') {
                $update_input = 1;
            } else {
                $update_input = 0;
            }
        }

        if($column == 'can_book') {
            if($action == 'Enable') {
                $update_input = 1;
            } else {
                $update_input = 0;
            }
        }

        $stmt = $this->db->prepare("UPDATE users SET {$column} = :input WHERE id = :user_id");
        
        $result = $stmt->execute(array(
            ':input' => $update_input,
            ':user_id' => $user_id
        ));

        return $result;

    }
    public function update_room() {

        $result = false;
        
        $room_id = $_POST['id'];
        $column = $_POST['setting'];
        $action = $_POST['action'];

        if($column == 'status') {
            if($action == 'Close') {
                $update_input = 0;
            } else {
                $update_input = 1;
            }
        }

        
        $stmt = $this->db->prepare("UPDATE rooms SET {$column} = :input WHERE id = :room_id");
        
        $result = $stmt->execute(array(
            ':input' => $update_input,
            ':room_id' => $room_id
        ));

        return $result;

    }
    public function change_site_name() {

        $page_title = $_POST['page_title'];
        $config_file = __SITE_PATH.'app/config/config.xml';
        $config = simplexml_load_file($config_file);
        $config->general->pagetitle = $page_title;
        $content = $config->asXML();
        $handle = fopen($config_file, 'w') or die('Cannot open file:  '.$config_file);
        fwrite($handle, $content);
        fclose($handle);

        return true;
    }

    
}
