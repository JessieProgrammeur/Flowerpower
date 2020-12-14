<?php

class database{

    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $db;
    
    // create class constants (admin and user)
    const ADMIN = 1;
    const USER = 2;
    const Employee = 3;

    public function __construct($host, $username, $password, $database, $charset){
        $this->host = $host; //localhost
        $this->username = $username; //root
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        try{
            // DSN connection method
            /*
            - mysql driver
            - host (localhost/127.0.0.1)
            - database (schema) name
            - charset
            */
            $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $this->db = new PDO($dsn, $this->username, $this->password);

            // echo "Database connection successfully established"; -> not nescessary to show this on the website!

        }catch(PDOException $e){
            // die and exit are equivalent
            // exit-> Output a message and terminate the current script
            die("Unable to connect: " . $e->getMessage());
        }
    }

    private function is_new_account($username){
        
        $stmt = $this->db->prepare('SELECT * FROM account WHERE username=:username');
        $stmt->execute(['username'=>$username]);
        $result = $stmt->fetch();

        if(is_array($result) && count($result) > 0){
            return false;
        }

        return true;
    }

    private function create_or_update_account($id, $type_id, $username, $email, $password){
        
        //todo: is null check ->update/insert
        // hash password to ensure password safety in db
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        // prepared statements =
        // only proper way to run a query when a variable is used.
        // use a placeholder when using variables. Two functions needed:
        /*
        prepare() -> returns PDOStatement object without data being attached to it.
        execute() -> will be able to get resulting data of statement (if applicable)
        */

        $sql = "INSERT INTO account VALUES (NULL, :type_id, :username, :email, :password, :created, :updated)";

        $statement = $this->db->prepare($sql);

        // current datetime. created_at and updated_at should initially be the same
        $created_at = $updated_at = date('Y-m-d H:i:s');

        $statement->execute([
            'type_id'=>$type_id,
            'username'=>$username, 
            'email'=>$email, 
            'password'=>$hashed_password, 
            'created'=> $created_at, 
            'updated'=> $updated_at
        ]);
        
        $account_id = $this->db->lastInsertId();
        return $account_id;
                
    }

    private function create_or_update_persoon($id, $account_id, $fname, $mname, $lname){
        //todo: is null check ->update/insert

        $sql = "INSERT INTO person VALUES (NULL, :account_id, :firstname, :middlename, :lastname, :created, :updated)";

        $statement = $this->db->prepare($sql);

        // current datetime. created_at and updated_at should initially be the same
        $created_at = $updated_at = date('Y-m-d H:i:s');

        $statement->execute([
            'account_id'=>$account_id, 
            'firstname'=>$fname, 
            'middlename'=>$mname, 
            'lastname'=> $lname, 
            'created'=> $created_at,
            'updated'=> $updated_at
        ]);
        
        $person_id = $this->db->lastInsertId();
        return $person_id;

    }
    public function sign_up($username, $type_id=self::USER, $firstname, $mname, $lastname, $email, $password){

       try{
           // create a database transaction
            $this->db->beginTransaction();

            // return error message is user already exists
            if(!$this->is_new_account($username)){
                return "Username already exists. Please pick another one, and try again.";
            }

            // created_at and updated_at by default the same

            // insert new account and person
            $account_id = $this->create_or_update_account(NULL, $type_id, $username, $email, $password);
            $this->create_or_update_persoon(NULL, $account_id, $firstname, $mname, $lastname);

            // commit database changes
            $this->db->commit();

            // check if there's a session (created in login, should only visit here in case of admin login)
            if(isset($_SESSION) && $_SESSION['usertype'] == self::ADMIN){
                return "New user has been succesfully added to the database";
            }

            //  // redirect user to the login form
            //  header('location: index.php');

            //  // make sure that further code isn't executed!
            //  exit;


       }catch(Exception $e){
        
           // there is a possibility that an error occurs.
           // when it does, we end here, in the catch.
           // undo applied database changes for data integrity
           $this->db->rollback();
           echo "Signup failed: " . $e->getMessage();
       }
    }

    private function is_admin($username){
        $sql = "SELECT type_id FROM account WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username'=>$username]);

        // result is an associative array (key-value pair)
        $result = $stmt->fetch();
        
        if($result['type_id'] == self::ADMIN){
            return true;
        }

        // user is not admin
        return false;
    }

    public function login($username, $password){
        $sql = "SELECT id, type_id, password FROM account WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username'=>$username]);

        // fetch should return an associative array (key, value pair)
        $result = $stmt->fetch();

        // check $result is an array
        if(is_array($result)){

            // apply count on if $result is an array
            if(count($result) > 0){

                // get hashed_password from database result with key 'password'
                $hashed_password = $result['password'];
                var_dump( password_verify($password, $hashed_password));

                // verife that user exists and that provided password is the same as the hashed password
                if($username && password_verify($password, $hashed_password)){
                    session_start();
    
                    // store userdata in session variables
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['usertype'] = $result['type_id'];
                    $_SESSION['loggedin'] = true;
    
                    // check if user is an administrator. If so, redirect to the admin page.
                    // if not administrator, redirect to user page.
                    if($this->is_admin($username)){
                        header("location: welcome_admin.php");
                        //make sure that code below redirect does not get executed when redirected.
                        exit;
                    }else{
                        //make sure that code below redirect does not get executed when redirected.
                        header("location: welcome_user.php");
                        exit;
                    }

                }else{
                    // returned an error message to show in span element in login form (index.php)
                    return "Incorrect username and/or password. Please fix your input and try again.";
                }
            }
        }else{
            // no matching user found in db. Make sure not to tell the user.
            return "Failed to login. Please try again";
        }
    }

    public function show_profile_details_user($username){

        $sql = "
            SELECT a.id, u.type, p.first_name, p.middle_name, p.last_name, a.username, a.email 
            FROM person as p 
            LEFT JOIN account as a
            ON p.account_id = a.id
            LEFT JOIN usertype as u
            ON a.type_id = u.id       
        ";

        if($username !== NULL){
            // query for specific user when a username is supplied
            $sql .= 'WHERE a.username = :username';
        }

        $stmt = $this->db->prepare($sql);

        // check if username is supplied, if so, pass assoc array to execute
        $username !== NULL ? $stmt->execute(['username'=>$username]) : $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

}
?>
