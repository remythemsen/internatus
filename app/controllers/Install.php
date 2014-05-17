<?php
class Install extends Controller {
    function Index() {

        // recheck for config file, to limit access after install.
        if(file_exists(__SITE_PATH.'/config/config.xml')) {
            $this->redirect_to('home');
        }

        
        // render installation index view
        $this->view->render('install/index', false);
    }

    function Database() {
        // recheck for config file, to limit access after install.
        if(file_exists(__SITE_PATH.'/config/config.xml')) {
            $this->redirect_to('install/administrator');
        }

        $this->view->render('install/db', false);
    }
    function Database_post() {
        
        // get variables

        $db_username = (isset($_POST['db_username']) ? $_POST['db_username'] : false);
        $db_password = (isset($_POST['db_password']) ? $_POST['db_password'] : false);
        $db_host = (isset($_POST['db_host']) ? $_POST['db_host'] : false);
        $db_name = (isset($_POST['db_name']) ? $_POST['db_name'] : false);

        // if all is true (has data);
        if($db_username && $db_password && $db_host && $db_name) {

            // check connection

            try{
                $db = new pdo( 'mysql:host='.$db_host.';dbname='.$db_name,
                                $db_username,
                                $db_password,
                                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(PDOException $ex){
                
                    $this->redirect_to('install/database');
                    $this->addNotification('danger', 'Could not connect to the Database');
                    die();

            }
        
            // prepare file

            $content = array(
                "<?xml version=\"1.0\" encoding=\"utf-8\"?>",
                "<application>",
                    "\t<db>",
                        "\t\t<username>{$db_username}</username>",
                        "\t\t<password>{$db_password}</password>",
                        "\t\t<host>{$db_host}</host>",
                        "\t\t<name>{$db_name}</name>",
                    "\t</db>",
                    "\t<general>",
                        "\t\t<pagetitle></pagetitle>",
                    "\t</general>",
                "</application>"
            );

            $content = implode($content, "\n"); 

            $config_file = __SITE_PATH."/config/config.xml";
            
            // opening a file (new)
            $handle = fopen($config_file, 'w') or die('Cannot open file:  '.$config_file);
            // writing $content to file
            fwrite($handle, $content);
            // closing the file.
            fclose($handle);

            // registering the new config file
            $this->registry->config = simplexml_load_file($config_file);

            $this->redirect_to('install/administrator');
        } else {
            $this->redirect_to('install/database');
            $this->addNotification('warning', 'All fields must be filled out.');
        }
 
    }

    function Administrator() {
        // check for existing admin:

        $db = new Database();

        $sth = $db->prepare("SELECT COUNT(*) FROM users");
        $sth->execute();

        if ( $sth->fetchColumn() > 0 ) {
                $this->redirect_to('install/settings');
                $this->addNotification('warning', 'A user was already created!');
                $db = null;
                die();
                exit();
        } else {
            $db = null;
            $this->view->render('install/administrator', false);
        }
        
    }

    function Administrator_post() {

        // get global vars from form.
        $username = (isset($_POST['username']) ? $_POST['username'] : false);
        $email = (isset($_POST['email']) ? $_POST['email'] : false);
        $password = (isset($_POST['password']) ? $_POST['password'] : false);
        
        //checking for data from form
        if($username && $email && $password) {
        
            // instanciate new db object.
            $db = new Database();
            
            // include migrations
            require(__SITE_PATH.'/includes/Migrations.php');

            // instanciate the handler, pass db reference.
            $migrationsHandler = new Migrations($db);
            
            // run migrations query.
            $migrationsHandler->run();

            // check if a user already exists

            $statement = $db->query("SELECT * FROM users");

            if ( $statement->rowCount() > 0 ) {
                $this->redirect_to('install/settings');
                $this->addNotification('warning', 'A user was already created!');
                die();
                exit();
            }

            // check if username already exists
            $statement = $db->prepare("SELECT username FROM users WHERE username = ? LIMIT 1");
            $statement->execute(array($username));
            if ( $statement->rowCount() == 0 ) {
                 // prepare statement
                $statement = $db->prepare("INSERT INTO users (username, password, email, is_admin, is_active) VALUES (?, ?, ?, ?, ?)");

                // execute statement
                $result = $statement->execute(array($username, MD5($password), $email, 1, 1));

            } else {
               $this->redirect_to('install/administrator'); 
               $this->addNotification('warning', 'Sorry, that username has already been taken :(');
               
            }
              
            // close db connection
            $db = null;

            if($result) {
                $this->redirect_to('install/settings');
            } else {
                throw new exception('Something went wrong with the insertion of data');
            }
        } else {
            $this->redirect_to('install/administrator');
            $this->addNotification('warning', 'All fields must be filled out.');
        }
    }
    
    function Settings() {
        if(!isset($this->registry->config)) {
            $this->redirect_to('install/database');
            $this->addNotification('danger', 'No configuration file was found!, please enter the following information');
        } else {

            $page_title = $this->registry->config->general->pagetitle;
            if(!isset($page_title) or $page_title == '') {
                $this->view->render('install/settings', false);
            } else {
                $this->redirect_to('home');
                $this->addNotification('warning', 'The Page title has already been set!, to change it, go to settings.');
            }
        }
    }

    function Settings_post() {
        // get global vars from form.
        $page_title = (isset($_POST['page_title']) ? $_POST['page_title'] : false);
        
        //checking for data from form
        if($page_title) {
            // setting new value for page title in config object.
            $this->registry->config->general->pagetitle = $page_title;
            // storing full object in var as xml
            $content = $this->registry->config->asXML();
            // overwriting config file with new xml
            $config_file = __SITE_PATH.'/config/config.xml';
            $handle = fopen($config_file, 'w') or die('Cannot open file:  '.$config_file);
            $result = fwrite($handle, $content);
            fclose($handle);

            // redirect to complete page
            if($result != false) {
                $this->redirect_to('install/complete');
            } else {
                throw new exception('Couldnt write to config file.');
            }
        } else {
            $this->redirect_to('install/settings');
            $this->addNotification('warning', 'All fields must be filled out.');

        }
    }

    function Complete() {
        $this->view->render('install/complete', false);

    }


}
?>
