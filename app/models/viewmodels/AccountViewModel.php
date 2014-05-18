<?php 

class AccountViewModel extends ViewModel {
    
    
    public function login() {

        $result = false;

        // getting post variables from form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // authenticating credentials, returning the account id.
        $account_id = $this->authenticate($username, $password);

        if($account_id != null) {
            // Setting the current account's id in the session.
            Session::set('account_id', $account_id);
            $result = true;
        }

        return $result;
    }

    public function authenticate($username, $password) {
        
        // prepared statement
        $statement = $this->db->prepare("SELECT * FROM accounts WHERE username = :username AND password = MD5(:password)");
       

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
            
            $account = $statement->fetch();

            if($account['is_active'] > 0) {
             
                return $account['id'];
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





    
    public function get_accounts() {
        
        $statement = $this->db->prepare('SELECT id, username, is_admin, is_active, can_book FROM accounts');

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
        $statement->execute();

        $records = $statement->fetchAll();

        echo json_encode($records);
    }
    public function update($setting) {
        // input
        $update_input = $_POST['update_input'];
        // need account id
        $account_id = Session::get('account_id');
        // setting (columns)
        $column = $setting;
        
        // setting MD5 encryption on password string.
        if($column == 'password') {
            $update_input = MD5($update_input);
        }

        if($column == 'username') {
            // check is username already exists:
            $statement = $this->db->prepare("SELECT username FROM accounts WHERE username = ? LIMIT 1");
            // execute
            $statement->execute(array($update_input));
            if ( $statement->rowCount() == 0 ) {
                // updating record in db
                $stmt = $this->db->prepare("UPDATE accounts SET {$column} = :user_input WHERE id = :account_id");
                $result = $stmt->execute(array(
                ':user_input' => $update_input,
                ':account_id' => $account_id
                ));
                return true;
            } else {
                return false;
            }
        } else {
        
            // updating record in db
            $stmt = $this->db->prepare("UPDATE accounts SET {$column} = :user_input WHERE id = :account_id");
            $result = $stmt->execute(array(
                ':user_input' => $update_input,
                ':account_id' => $account_id
            ));

            return true;
        }
        
    }
    // ajax call to update the accounts from admin menu
    public function update_user() {

        $result = false;
        
        $account_id = $_POST['id'];
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

        $stmt = $this->db->prepare("UPDATE accounts SET {$column} = :input WHERE id = :account_id");
        
        $result = $stmt->execute(array(
            ':input' => $update_input,
            ':account_id' => $account_id
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