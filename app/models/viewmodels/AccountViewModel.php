<?php 

class AccountViewModel extends ViewModel {

    public function create() {
        // get + trim vars
        $username = (isset($_POST['username']) ? trim($_POST['username']) : false);
        $email = (isset($_POST['email']) ? trim($_POST['email']) : false);
        $password = (isset($_POST['password']) ? trim($_POST['password']) : false);

        // Validation

        if(Validator::check(array(
            'username' => $username,
            'password' => $password,
            'email' => $email
        ))) {
            $stmt = $this->db->prepare("INSERT INTO accounts (username, password, email, is_admin, is_active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array($username, Hash::make($password), $email, 0, 1));
            Notifier::add('success', 'Congratulations, your account has been created, now login with your new credentials.');
            return true;
        }
    }
}