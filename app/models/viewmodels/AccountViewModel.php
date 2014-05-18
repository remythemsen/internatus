<?php 

class AccountViewModel extends ViewModel {

    public function create() {

        $username = (isset($_POST['username']) ? trim($_POST['username']) : false);
        $email = (isset($_POST['email']) ? trim($_POST['email']) : false);
        $password = (isset($_POST['password']) ? trim($_POST['password']) : false);
        
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
                $statement->execute(array($username, Hash::make($password), $email, 0, 1));

                Notifier::add('success', 'Congratulations, your account has been created, now login with your new credentials.');
                return true;
                exit();

            } else {
                Notifier::add('warning', 'Account name has already been taken');
                return false; 'Account name is already taken';
                exit();
            }
        } else {
            Notifier::add('warning', 'All fields are required');
            return false;
            exit();
        }
    }
}