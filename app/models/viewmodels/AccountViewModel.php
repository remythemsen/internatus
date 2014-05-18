<?php 

class AccountViewModel extends ViewModel {

    public function create() {

        $result = false;
        
        $username = (isset($_POST['username']) ? trim($_POST['username']) : false);
        $email = (isset($_POST['email']) ? trim($_POST['email']) : false);
        $password = (isset($_POST['password']) ? MD5(trim($_POST['password'])) : false);
        
        // checking for all required data
        if($username && $email && $password) {

            // check is username already exists:
            $statement = $this->db->prepare("SELECT username FROM accounts WHERE username = ? LIMIT 1");
            // execute
            $statement->execute(array($username));
            if ( $statement->rowCount() == 0 ) {
                // prepared statement
                $statement = $this->db->prepare("INSERT INTO accounts (username, password, email, is_admin, is_active) VALUES (?, ?, ?, ?, ?)");
           
                // execute statement
                $statement->execute(array($username, $password, $email, 0, 1));

                $result = true;
                
            } else {
                $result = 'Account name is already taken';
            }
        } else {
            $result = 'Some fields are not filled out!';
        }
        // returning the result to controller
        return $result;
    }





    
}