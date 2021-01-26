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
    
    // Customer functions //
        
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

     // Employee functions //

     private function is_new_employee($username){
        
        $stmt = $this->db->prepare('SELECT * FROM employee WHERE username=:username');
        $stmt->execute(['username'=>$username]);
        $result = $stmt->fetch();

        if(is_array($result) && count($result) > 0){
            return false;
        }

        return true;
    }

    private function create_or_update_employee($id, $usertype_id, $initials, $prefix, $last_name, $username, $password){
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO employee VALUES (NULL, :usertype_id, :initials, :prefix, :last_name, :username, :password, :created, :updated)";

        $statement = $this->db->prepare($sql);

        $created_at = $updated_at = date('Y-m-d H:i:s');

        $statement->execute([
            'usertype_id'=>$usertype_id,
            'initials'=>$initials, 
            'prefix'=>$prefix, 
            'last_name'=>$last_name,
            'username'=>$username, 
            'password'=>$hashed_password, 
            'created'=> $created_at, 
            'updated'=> $updated_at
        ]);
        
        $employee_id = $this->db->lastInsertId();
        return $employee_id;
                
    }

     public function sign_upemp($username, $usertype_id=self::USER, $initials, $prefix, $last_name, $password){

        try{
            
             $this->db->beginTransaction();
 
             if(!$this->is_new_employee($username)){
                 return "Username already exists. Please pick another one, and try again.";
             }
 
             $employee_id = $this->create_or_update_employee(NULL, $usertype_id, $initials, $prefix, $last_name, $username, $password);
             
             $this->db->commit();
 
             if(isset($_SESSION) && $_SESSION['usertype'] == self::ADMIN){
                 return "New user has been succesfully added to the database";
             }

        }catch(Exception $e){
         
            $this->db->rollback();
            echo "Signup failed: " . $e->getMessage();
        }
     }

     public function loginemp($username, $password){
        $sql = "SELECT id, password FROM employee WHERE username = :username";

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
                        header("location: welcome_emp.php");
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

     // Admin functions //

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

    // Login //
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

    // Show data user //

    public function show_profile_details_user($username){

        $sql = "
        SELECT u.type, 
        c.id, 
        c.initials, 
        c.prefix, 
        c.last_name, 
        c.address, 
        c.postal_code, 
        c.residence, 
        c.birth_date, 
        c.email, 
        c.username
        FROM usertype AS u 
        LEFT JOIN customer AS c 
        ON u.type = c.id
        ";
        
        if($username !== NULL){
            $sql .= 'WHERE c.username = :username';
        }
        
        $stmt = $this->db->prepare($sql);

        $username !== NULL ? $stmt->execute(['username'=>$username]) : $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }

    

    private function create_or_update_product($id, $product, $price){

        $sql = "INSERT INTO employee VALUES (NULL, :product, :price, :created, :updated)";

        $statement = $this->db->prepare($sql);

        $created_at = $updated_at = date('Y-m-d H:i:s');

        $statement->execute([
            'product'=>$product,
            'price'=>$price,
            'created'=> $created_at, 
            'updated'=> $updated_at
        ]);
        
        $product_id = $this->db->lastInsertId();
        return $product_id;
                
    }
    
    public function show_product($name){

        $sql .= 'SELECT * FROM product ORDER BY id ASC';
        
        $stmt = $this->db->prepare($sql);

        $name !== NULL ? $stmt->execute(['name'=>$name]) : $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }
    
}

?>