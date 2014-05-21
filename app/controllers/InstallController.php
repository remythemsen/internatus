<?php
class InstallController extends Controller {
    function getIndex() {

        // recheck for config file, to limit access after install.
        if(file_exists(__SITE_PATH.'app/config/config.xml')) {
            URL::redirect('home');
        }

        
        // render installation index view
        $this->view->render('install/index', false);
    }

    function Database() {
        // recheck for config file, to limit access after install.
        if(file_exists(__SITE_PATH.'app/config/config.xml')) {
            URL::redirect('install/administrator');
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
                
                    URL::redirect('install/database');
                    Notifier::add('danger', 'Could not connect to the Database');
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
                        "\t\t<salt></salt>",
                    "\t</general>",
                "</application>"
            );

            $content = implode($content, "\n"); 

            $config_file = __SITE_PATH."app/config/config.xml";
            
            // opening a file (new)
            $handle = fopen($config_file, 'w') or die('Cannot open file:  '.$config_file);
            // writing $content to file
            fwrite($handle, $content);
            // closing the file.
            fclose($handle);


            URL::redirect('install/administrator');
        } else {
            URL::redirect('install/database');
            Notifier::add('warning', 'All fields must be filled out.');
        }
 
    }

    function Administrator() {
        // check for existing admin:

        $db = new Database();

        $sth = $db->prepare("SELECT COUNT(*) FROM accounts");
        $sth->execute();

        if ( $sth->fetchColumn() > 0 ) {
                URL::redirect('install/settings');
                Notifier::add('warning', 'An administrative account was already created!');
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
            require(__SITE_PATH.'app/helpers/Migrations.php');

            // instanciate the handler, pass db reference.
            $migrations = new Migrations($db);

            // run migrations query.
            $migrations->run();

            // check if an account already exists

            $statement = $db->query("SELECT * FROM accounts");

            if ( $statement->rowCount() > 0 ) {
                URL::redirect('install/settings');
                Notifier::add('warning', 'An account was already created!');
                die();
                exit();
            }

            // check if username already exists
            $statement = $db->prepare("SELECT username FROM accounts WHERE username = ? LIMIT 1");
            $statement->execute(array($username));
            if ( $statement->rowCount() == 0 ) {
                 // prepare statement
                $statement = $db->prepare("INSERT INTO accounts (username, password, email, is_admin, is_active) VALUES (?, ?, ?, ?, ?)");

                // execute statement
                $result = $statement->execute(array($username, Hash::make($password), $email, 1, 1));

            } else {
               URL::redirect('install/administrator');
               Notifier::add('warning', 'Sorry, that username has already been taken :(');
               
            }
              
            // close db connection
            $db = null;

            if($result) {
                URL::redirect('install/settings');
            } else {
                throw new exception('Something went wrong with the insertion of data');
            }
        } else {
            URL::redirect('install/administrator');
            Notifier::add('warning', 'All fields must be filled out.');
        }
    }
    
    function Settings() {
        if(!Config::get()) {
            URL::redirect('install/database');
            Notifier::add('danger', 'No configuration file was found!, please enter the following information');
        } else {

            $page_title = Config::get()->general->pagetitle;
            if(!isset($page_title) or $page_title == '') {
                $this->view->render('install/settings', false);
            } else {
                URL::redirect('home');
                Notifier::add('warning', 'The Page title has already been set!, to change it, go to settings.');
            }
        }
    }

    function Settings_post() {
        // get global vars from form.
        $page_title = (isset($_POST['page_title']) ? $_POST['page_title'] : false);
        $salt = (isset($_POST['salt']) ? $_POST['salt'] : false);

        //checking for data from form
        if($page_title) {

            $config = Config::get();
            // setting new value for page title and salt in config object.
            $config->general->pagetitle = $page_title;
            $config->general->salt = $salt;
            // storing full object in var as xml
            $content = $config->asXML();
            // overwriting config file with new xml
            $config_file = __SITE_PATH.'app/config/config.xml';
            $handle = fopen($config_file, 'w') or die('Cannot open file:  '.$config_file);
            $result = fwrite($handle, $content);
            fclose($handle);

            // redirect to complete page
            if($result != false) {
                URL::redirect('install/complete');
            } else {
                throw new exception('Couldnt write to config file.');
            }
        } else {
            URL::redirect('install/settings');
            Notifier::add('warning', 'All fields must be filled out.');

        }
    }

    function Complete() {
        $this->view->render('install/complete', false);

    }


}
?>
