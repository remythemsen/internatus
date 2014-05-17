<?php 
class BookingsViewModel extends ViewModel {
    
    function get_bookings() {

        $statement = $this->db->prepare("
            SELECT *
            FROM bookings
            WHERE user_id = :id
            ORDER BY bookings.time_to ASC 
        ");

        $statement->setFetchMode(PDO::FETCH_CLASS, "Booking");

        $user_id = Session::get('user_id');
        
        $statement->execute(array(':id'=>$user_id));

        $bookings = $statement->fetchAll();

        return $bookings;
    }


    public function delete_booking($booking_id = false) {
        $id = (isset($_POST['id']) ? $_POST['id'] : $booking_id);
        $statement = $this->db->prepare('DELETE FROM bookings WHERE id = "'.$id.'"');
        $result = $statement->execute();
    }

    public function get_rooms() {
    
        $stmt = $this->db->prepare('SELECT * FROM rooms');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

        
    public function create() {
        $result = false;

        // getting variables from global post

        $room_id = $_POST['room_id'];
        $date = $_POST['date'];
        $time_from = $_POST['time-from'];
        $time_to = $_POST['time-to'];
        $user_id = Session::get('user_id'); // (and session)

        // reformat entries to datetime acceptable strings

        // YYYY-MM-DD HH:MM:SS
        $time_from = $date." ".$time_from.":00";
        $time_to = $date." ".$time_to.":00";

        $comparable_time_from = strtotime($time_from);
        $comparable_time_to = strtotime($time_to);

        if($comparable_time_from < time()) {
            return false;
            die();
            exit();
        }
        if($comparable_time_from > $comparable_time_to) {
            return false;
            die();
            exit();
        }

        // prepared statement
        $statement = $this->db->prepare("INSERT INTO bookings (user_id, room_id, time_from, time_to) VALUES (?, ?, ?, ?)"); 
        // execute statement
        $result = $statement->execute(array($user_id, $room_id, $time_from, $time_to));

        if($result) {
            $stmt = $this->db->prepare("UPDATE rooms SET booking_count = booking_count + 1 WHERE id = :room_id");
            $stmt->execute(array(':room_id' => $room_id));
        }
 
        return $result;
    }
    public function get_free_rooms() {

        // getting the get variables sent by ajax get request.
        
        $date = $_GET['date'];
        $time_from = $date.' '.$_GET['time-from'].':00';
        $time_to = $date.' '.$_GET['time-to'].':00';

        // query = return rooms that isn't occupied.
        
        

        
        $statement = $this->db->prepare("
            SELECT * 
            FROM rooms 
            WHERE NOT EXISTS (SELECT 1 FROM bookings
            WHERE rooms.id = bookings.room_id
            AND ( :time_from BETWEEN bookings.time_from AND bookings.time_to
                OR :time_to BETWEEN bookings.time_from AND bookings.time_to)
            )
            AND status != 0
        ");

        // setting fetchmode to associative array.
            
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
        $statement->execute(array(':time_from' => $time_from, ':time_to' => $time_to));

        // return the records from the query, and store the array in $records.
        
        $records = $statement->fetchAll();


        // json encode for easy javascript 'object' conversion.
        
        echo json_encode($records);

    }

    public function get_unavailable_rooms() {
        
        // getting the get variables sent by ajax get request.
        
        $date = $_GET['date'];
        $time_from = $date.' '.$_GET['time-from'].':00';
        $time_to = $date.' '.$_GET['time-to'].':00';
        

        // prepare statement, (query) for getting rooms which is not available.
        $statement = $this->db->prepare("
            SELECT bookings.room_id, rooms.name, users.username, bookings.time_to, users.email
            FROM bookings 
            INNER JOIN rooms ON bookings.room_id = rooms.id
            INNER JOIN users ON bookings.user_id = users.id
            WHERE :time_from 
            BETWEEN bookings.time_from 
            AND bookings.time_to 
            OR :time_to
            BETWEEN bookings.time_from 
            AND bookings.time_to
            GROUP BY 
            bookings.room_id
        ");
            
        // setting fetchmode to associative array.
            
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
        $statement->execute(array(':time_from' => $time_from, ':time_to' => $time_to));

        // return the records from the query, and store the array in $records.
        
        $records = $statement->fetchAll();


        // json encode for easy javascript 'object' conversion.
        
        echo json_encode($records);

    }

    public function send_booking_request() {
        // get global vars
        $owner_user_id = $_POST['user_id'];

        // get users email:

        $stmt = $this->db->prepare("SELECT email FROM users WHERE id = :user_id");
        $stmt->execute(array(':user_id' => $owner_user_id));
        $email = $stmt->fetchColumn();

        // get logged in user
        
        $logged_in_user_id = Session::get('user_id');
        $stmt = $this->db->query("SELECT * FROM users WHERE id = {$logged_in_user_id}");
        $logged_in_user = $stmt->fetch();
        
       
        
        $subject = $logged_in_user['username'].' wants your booking';
        $message = "hey this an autogenerated message: {$logged_in_user['username']} wants your booking, please contact him/her on this email: {$logged_in_user['email']}";
        
        // send email!
        mail($email, $subject, $message);


    }


   
}

?>
