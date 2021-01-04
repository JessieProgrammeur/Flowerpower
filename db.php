<?php

class db{

    private $host;
    private $user;
	private $database;
	private $password;
	private $db;

	const ADMIN = 1;
    const USER = 2;
    const EMPLOYEE = 3;
    
    public function __construct($host, $user, $database, $password){
        $this->host = $host;
		$this->user = $user;
		$this->database = $database;
        $this->password = $password;

        try{
			$this->db = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
			echo var_dump($this->db);
        } 
        catch(PDOException $e){
            echo $e->getMessage();
        }
	}
        
    private function is_new_customer($username){
        
        $stmt = $this->db->prepare('SELECT * FROM customer WHERE username=:username');
        $stmt->execute(['username'=>$username]);
        $result = $stmt->fetch();

        if(is_array($result) && count($result) > 0){
            return false;
        }

        return true;
    }

    private function create_or_update_customer($id, $usertype_id, $initials, $prefix, $last_name, $address, $postal_code, $residence, $birth_date, $email, $username, $password){
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customer VALUES (NULL, :usertype_id, :initials, :prefix, :last_name, :address, :postal_code, :residence, :birth_date, :email, :username, :password, :created, :updated)";

        $statement = $this->db->prepare($sql);

        $created_at = $updated_at = date('Y-m-d H:i:s');

        $statement->execute([
            'usertype_id'=>$usertype_id,
            'initials'=>$initials, 
            'prefix'=>$prefix, 
            'last_name'=>$last_name, 
            'address'=>$address,
            'postal_code'=>$postal_code,
            'residence'=>$residence,  
            'birth_date'=>$birth_date,
            'email' =>$email, 
            'username'=>$username, 
            'password'=>$hashed_password, 
            'created'=> $created_at, 
            'updated'=> $updated_at
        ]);
        
        $customer_id = $this->db->lastInsertId();
        return $customer_id;
                
    }
    
    public function sign_up($username, $usertype_id=self::USER, $initials, $prefix, $last_name, $address, $postal_code, $residence, $birth_date, $email, $password){

        try{
            
             $this->db->beginTransaction();
 
             if(!$this->is_new_customer($username)){
                 return "Username already exists. Please pick another one, and try again.";
             }
 
             $customer_id = $this->create_or_update_customer(NULL, $usertype_id, $initials, $prefix, $last_name, $address, $postal_code, $residence, $birth_date, $email, $username, $password);
             
             $this->db->commit();
 
             if(isset($_SESSION) && $_SESSION['usertype'] == self::ADMIN){
                 return "New user has been succesfully added to the database";
             }

        }catch(Exception $e){
         
            $this->db->rollback();
            echo "Signup failed: " . $e->getMessage();
        }
     }

	private function is_admin($username){
        $sql = "SELECT usertype_id FROM customer WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username'=>$username]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result['usertype_id'] == self::ADMIN){
            return true;
        }

        return false;
    }

	public function login($username, $password){
        $sql = "SELECT id, password FROM customer WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username'=>$username]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(is_array($result)){
            
            if(count($result) > 0){
                
                    $hashed_password = $result['password'];
                    var_dump($hashed_password);
                    var_dump( password_verify($password, $hashed_password));

                if($username && password_verify($password, $hashed_password)){
                    session_start();

                    $_SESSION['id'] = $result['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['usertype'] = $result['usertype_id'];
                    $_SESSION['loggedin'] = true;
                    
                    if($this->is_admin($username)){
                        header("location: welcome_admin.php");
                        
                        exit;
                    }else{
                        header("location: welcome_user.php");
                        exit;
                    }

                }else{
                    return "Incorrect username and/or password. Please fix your input and try again.";
                }
            }
        }else{
            return "Failed to login. Please try again";
        }
    }

    public function show_profile_details_user($username){

        $sql = "
        SELECT u.usertype_id, c.id, c.initials, c.prefix, c.last_name, c.address, c.postal_code, c.residence, c.birth_date, c.email, c.username 
        FROM usertype as u
        LEFT JOIN customer as c
        ON u.usertype_id = c.id
        ";
        
        if($username !== NULL){
            $sql .= 'WHERE c.username = :username';
        }

        $stmt = $this->db->prepare($sql);

        $username !== NULL ? $stmt->execute(['username'=>$username]) : $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($results);
        return $results;
    }
}
?>