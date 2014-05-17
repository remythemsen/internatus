<?php 

class HomeViewModel extends ViewModel {

    // order by, (sql passed as string), xhr = if json encoding).
    public function get_rooms() {

        // selecting from a view
        $statement = $this->db->prepare("
            SELECT * FROM 
            top_3_rooms
        ");

        $statement->execute();

        $rooms = $statement->fetchAll(PDO::FETCH_CLASS, "Room");
        
        return $rooms;
    }
    
    function get_bookings() {

        $statement = $this->db->prepare("
            SELECT bookings.id, rooms.name, rooms.id AS room_id, bookings.time_from, bookings.time_to
            FROM bookings
            INNER JOIN rooms
            ON bookings.room_id=rooms.id
            WHERE user_id = :id
            ORDER BY bookings.time_to ASC
            ");


        $user_id = Session::get('user_id');
        
        $statement->execute(array(':id'=>$user_id));

        return $statement->fetchAll(PDO::FETCH_CLASS, "Booking");

    }

}
?>
