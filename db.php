<?php

class db{

    private $host;
    private $user;
	private $database;
	private $password;
	private $db;

	// create class constants ( admin, user and employee)
    const ADMIN = 1;
    const USER = 2;
    const EMPLOYEE = 3;
    
    public function __construct($host, $user, $database, $password){
        $this->host = $host;
		$this->user = $user;
		$this->database = $database;
        $this->password = $password;

        try{
            // connection method
			$this->db = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
        } 
        catch(PDOException $e){
            die("Unable to connect: " . $e->getMessage());
        }
    }
    
    // Customer functions //
        
    private function is_new_customer($username){
        
        $stmt = $this->db->prepare('SELECT * FROM customer WHERE username=:username');
        $stmt->execute(['username'=>$username]);

        // result is an associative array (key-value pair)
        $result = $stmt->fetch();

        if(is_array($result) && count($result) > 0){
            return false;
        }

        return true;
    }

    private function create_or_update_customer($id, $usertype_id, $initials, $prefix, $last_name, $address, $postal_code, $residence, $birth_date, $email, $username, $password){
        
        // hash password to ensure password safety
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // insert into customer query
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
            
            // create a database transaction
             $this->db->beginTransaction();
            
             // make sure to check if it's a non-existing customer
             if(!$this->is_new_customer($username)){
                 return "Username already exists. Please pick another one, and try again.";
             }
             
             // insert into table customer
             $customer_id = $this->create_or_update_customer(NULL, $usertype_id, $initials, $prefix, $last_name, $address, $postal_code, $residence, $birth_date, $email, $username, $password);
             
             // commit database changes
             $this->db->commit();
             
             // check if there's a session (created in login, should only visit here in case of admin)
             if(isset($_SESSION) && $_SESSION['usertype'] == self::ADMIN){
                 return "New user has been succesfully added to the database";
             }

             // user gets redirected to login if method is not called by admin. 
            header('location: signup.php');
            // exit makes sure that further code isn't executed.
            exit;

        }catch(Exception $e){
            
            // rollback database changes in case of an error to maintain data integrity.
            $this->db->rollback();
            echo "Signup failed: " . $e->getMessage();
        }
     }

     // Employee functions //

     private function is_new_employee($username){
        
        // get all from table employee where username equals username
        $stmt = $this->db->prepare('SELECT * FROM employee WHERE username=:username');
        $stmt->execute(['username'=>$username]);

        // result is an associative array (key-value pair)
        $result = $stmt->fetch();

        // check if $result is an array and is bigger than 0
        if(is_array($result) && count($result) > 0){
            return false;
        }
        return true;
    }

    private function create_or_update_employee($id, $usertype_id, $initials, $prefix, $last_name, $username, $password){
        
        // hash password to ensure password safety
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // insert into employee query
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
        
        // LastInsertId to get last inserted id and add one new
        $employee_id = $this->db->lastInsertId();
        return $employee_id;
                
    }

     public function sign_upemp($username, $usertype_id=self::EMPLOYEE, $initials, $prefix, $last_name, $password){

        try{
            // create a database transaction
             $this->db->beginTransaction();
            
             // check if username is not already in database
             if(!$this->is_new_employee($username)){
                 return "Username already exists. Please pick another one, and try again.";
             }
             
             // insert into employee query
             $employee_id = $this->create_or_update_employee(NULL, $usertype_id, $initials, $prefix, $last_name, $username, $password);
             
             $this->db->commit();
             
             // check if there's a session (created in login, should only visit here in case of employee)
             if(isset($_SESSION) && $_SESSION['usertype'] == self::EMPLOYEE){
                 return "New user has been succesfully added to the database";
             }

        }catch(Exception $e){
            
            // rollback database changes in case of an error to maintain data integrity.
            $this->db->rollback();
            echo "Signup failed: " . $e->getMessage();
        }
     }

     public function loginemp($username, $password){
        
        // get is and password from table employee where username equals username
        $sql = "SELECT id, password FROM employee WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username'=>$username]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // check if $result is an array
        if(is_array($result)){
            
            // check if $result is more than 0
            if(count($result) > 0){
                    // get hashed password
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
        // get id, usertype_id and password from account
        $sql = "SELECT id, password FROM customer WHERE username = :username";

        // prepare returns an empty statement object. there is no data stored in $stmt.
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username'=>$username]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // check $result is an array
        if(is_array($result)){
            
            if(count($result) > 0){

                // get hashed_password from database result with key 'password'
                    $hashed_password = $result['password'];
                    var_dump($hashed_password);
                    var_dump( password_verify($password, $hashed_password));

                // verify that user exists and that provided password is the same as the hashed password
                if($username && password_verify($password, $hashed_password)){
                    session_start();

                    $_SESSION['id'] = $result['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['usertype'] = $result['usertype_id'];
                    $_SESSION['loggedin'] = true;
                    
                    // check if user is an administrator. If so, redirect to the admin page.
                    // if not administrator, redirect to user page.
                    if($this->is_admin($username)){
                        header("location: welcome_admin.php");
                        //make sure that code below redirect does not get executed when redirected.
                        exit;
                    }else{
                        header("location: welcome_user.php");
                        exit;
                    }

                }else{
                    // returned an error message to show in span element in login form (index.php).
                    return "Incorrect username and/or password. Please fix your input and try again.";
                }
            }
        }else{
            // no matching user found in db. Make sure not to tell the user directly.
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

    public function show_profile_details_store(){

        $sql = "
        SELECT residence FROM store";
       
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

    public function show_profile_details_customers(){

        $sql = "
        SELECT * FROM customer";
       
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

        $sql = "SELECT product_product,
        product_price,
        invoiceline_amount,
        store_name,
        store_address,
        store_postal_code,
        store_residence,
        invoice_date
        FROM product 
        INNER JOIN invoiceline ON product.product_id = invoiceline.product_id
        INNER JOIN store ON invoiceline.product_id = store.product_id
        INNER JOIN invoice ON product.produt_id = invoice.invoice_id
        ORDER BY product_product, product_price";
       
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

     public function sign_up_order($store_id, $amount, $customer_id, $employee_id){

        try{
            
             $this->db->beginTransaction();
            
             $product_id = $this->create_or_update_order(NULL, $store_id, $amount, $customer_id, $employee_id);
             
             $this->db->commit();
 
        }catch(Exception $e){
         
            $this->db->rollback();
            echo "Signup failed: " . $e->getMessage();
        }
     }

     private function create_or_update_order($id, $store_id, $amount, $customer_id, $employee_id){

        $sql = "INSERT INTO orders VALUES (NULL, :store_id, :amount, :customer_id, :employee_id, :created, :updated)";

        $statement = $this->db->prepare($sql);

        $created_at = $updated_at = date('Y-m-d H:i:s');

        $statement->execute([
            'store_id'=>$store_id,
            'amount'=>$amount,
            'customer_id'=>$customer_id,
            'employee_id'=>$employee_id,
            'created'=> $created_at, 
            'updated'=> $updated_at
        ]);
        
        $order_id = $this->db->lastInsertId();
        return $order_id;       
    }


     

     public function select($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);

        $stmt->execute($named_placeholder);
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
     }

     public function select1($statement){

        $stmt = $this->db->prepare($statement);

        $stmt->execute($statement);
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

     public function update_or_delete_customer($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);
        $stmt->execute($named_placeholder);
        header('location:overzicht_users.php');
        exit();
     }

     public function update_or_delete_order($statement, $named_placeholder){

        $stmt = $this->db->prepare($statement);
        $stmt->execute($named_placeholder);
        header('location:overzicht_bestellingen.php');
        exit();
     }

     // PRODUCT TOEVOEGEN MET VALIDATION END
     public function getAllProduct() {
        try {
            $sql = "SELECT * FROM product ORDER BY id DESC LIMIT ?,?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
            $stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
            $stmt->execute();

            $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $products;
        } catch (Exception $e) {
            die("Oh noes! There's an error in the query!");
        }
    }
    
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


    public function show_producten(){

        $sql = 'SELECT * FROM product';
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }

    public function show_details_customers(){

        $sql = "
        SELECT * FROM customer ORDER BY id ASC";
       
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }

    
}

?>