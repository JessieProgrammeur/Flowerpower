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

     public function sign_upemp($username, $usertype_id=self::EMPLOYEE, $initials, $prefix, $last_name, $password){

        try{
            
             $this->db->beginTransaction();
 
             if(!$this->is_new_employee($username)){
                 return "Username already exists. Please pick another one, and try again.";
             }
 
             $employee_id = $this->create_or_update_employee(NULL, $usertype_id, $initials, $prefix, $last_name, $username, $password);
             
             $this->db->commit();
 
             if(isset($_SESSION) && $_SESSION['usertype'] == self::EMPLOYEE){
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

    public function show_profile_details_user(){

        $sql = "
        SELECT * FROM customer";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }

    public function show_profile_details_employee(){

        $sql = "
        SELECT * FROM employee";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $results;
    }

    public function show_profile_details_order(){

        $sql = "
        SELECT * FROM orders";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $results;
    }

    public function show_profile_details_product(){

        $sql = "
        SELECT * FROM product";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $results;
    }

    public function show_profile_details_invoice(){

        $sql = "
        SELECT * FROM invoice";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $results;
    }

    public function show_details_product(){

        $sql = "
        SELECT product, price FROM product ORDER BY id ASC";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }


    // PRODUCT TOEVOEGEN MET VALIDATION

    private function is_new_product($product){
        
        $stmt = $this->db->prepare('SELECT * FROM product WHERE product=:product');
        $stmt->execute(['product'=>$product]);
        $result = $stmt->fetch();

        if(is_array($result) && count($result) > 0){
            return false;
        }

        return true;
    }
    
    // todo: deze functie is voor de admin om producten bij te maken //
    private function create_or_update_product($id, $product, $price){

        $sql = "INSERT INTO product VALUES (NULL, :product, :price, :created, :updated)";

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

    public function sign_up_product($product, $price){

        try{
            
             $this->db->beginTransaction();
            
             if(!$this->is_new_product($product)){
                 return "Product already exists. Please pick another one, and try again.";
             }
 
             $product_id = $this->create_or_update_product(NULL, $product, $price);
             
             $this->db->commit();
 
        }catch(Exception $e){
         
            $this->db->rollback();
            echo "Signup failed: " . $e->getMessage();
        }
     }


     

     public function select($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);

        $stmt->execute($named_placeholder);
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
     }

     public function update_or_delete_product($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);
        $stmt->execute($named_placeholder);
        header('location:overzicht_artikelen.php');
        exit();
     }

     public function update_or_delete_employee($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);
        $stmt->execute($named_placeholder);
        header('location:overzicht_medewerker.php');
        exit();
     }

     public function update_or_delete_order($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);
        $stmt->execute($named_placeholder);
        header('location:overzicht_bestellingen.php');
        exit();
     }



     // edit_product.php
    //  private function updated_product($id, $product, $price){

    //     $sql = "UPDATE product SET (NULL, :product, :price, :created, :updated WHERE id=id)";

    //     $statement = $this->db->prepare($sql);

    //     $created_at = $updated_at = date('Y-m-d H:i:s');

    //     $statement->execute([
    //         'product'=>$product,
    //         'price'=>$price,
    //         'created'=> $created_at, 
    //         'updated'=> $updated_at,
    //     ]); 
        
    //     // $product_id = $this->db->lastInsertId();
    //     // return $product_id;       
    // }
     
    // private function get_id_product($id){
        
    //     $stmt = $this->db->prepare('SELECT id FROM product WHERE id=' . $id);
    //     $stmt->execute(['id'=>$id]);
    //     $result = $stmt->fetch();


    //     return $result;
    // }
    
    // public function update_product($id, $product, $price){

    //     try{
            
    //          $this->db->beginTransaction();
            
    //          if(!$this->get_id_product($id)){
    //              return "Product id error.try something else";
    //          }
 
    //          $product_id = $this->updated_product($id, $product, $price);
             
    //          $this->db->commit();
 
    //     }catch(Exception $e){
         
    //         $this->db->rollback();
    //         echo "Signup failed: " . $e->getMessage();
    //     }
    //  }
     
     
     // PRODUCT TOEVOEGEN MET VALIDATION END

    public function create_order_customer($id, $product_id, $store_id, $amount, $customer_id, $employee_id){

        try{
        
        $sql = "INSERT INTO orders VALUES (NULL, :product_id, :store_id, :amount, :customer_id, :employee_id, :picked_up, :created_at, :updated_at)";

        $statement = $this->db->prepare($sql);

        $picked_up = $created_at = $updated_at = date('Y-m-d H:i:s');
        
        $statement->execute([
            'product_id'=>$product_id,
            'store_id'=>$store_id,
            'amount'=>$amount,
            'customer_id'=>$customer_id,
            'employee_id'=>$employee_id,
            'picked_up'=> $picked_up, 
            'created_at'=> $created_at, 
            'updated_at'=> $updated_at
        ]);
        
        $orders_id = $this->db->lastInsertId();
        return $orders_id;  

        }catch(PDOException $e){
        $this->db->rollback();
        echo " failed: " . $e->getMessage();
    }
}


    public function show_product($name){

        $sql = 'SELECT * FROM product ORDER BY id ASC';
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute($sql);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }
    
}

?>