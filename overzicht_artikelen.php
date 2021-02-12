<?php

    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: index.php');
    exit;
    }   

    include 'db.php';
    include 'validation.php';

    $db = new db("localhost", "root", "flowerpower", "");
     
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){
    
    $fields = ['username', 
    'password'
    ];
    
    $obj = new Validation();

    $fields_validated = $obj->field_validation($fields);

   if($fields_validated){
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);

      $loginError = $db->login($username, $password);
    }
  }


  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add']) && !empty($_POST['add'])){

    // $field = [
    //     'amount'
    // ];
    
    // $obj = new helper();

    // $field_validated = $obj->field_validation($field);
    

    if (isset($_POST['amount'])){
        
        $amount = $_POST['amount'];
        echo $amount;
        // $coc = $db->create_order_customer($amount); ToDO: deze data naar winkelwagen. niet naar db direct

        }
    }

//   if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){

//     $fields = [
//         'amount, picked_up'
//     ];

//     $obj = new Helper();

//     $fields_validated = $obj->field_validation($fields);
    
//     if($fields_validated){
        
//         $amount = isset($_POST['amount']);
//         $picked_up = isset($_POST['picked_up']) ? trim(strtolower($_POST['picked_up'])) : NULL;

//             $db = new db('localhost', 'root', 'flowerpower', '');

//             $msg = $db->create_order_customer($amount, $picked_up);
        
//     }else{
//         $missingFieldError = "Input for one of more fields missing. Please provide all required values and try again.";
//     }
//    };

// todo: zorgen dat na klik button data gesaved wordt in db - omzetten naar nieuwe functie uit db.
//   if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){
//     $fields = ['product', 'price'];

//     $obj = new Helper();

//     $fields_validated = $obj->field_validation($fields);

    
//     if($fields_validated){
//       $product = trim($_POST['product']);
//       $price = trim($_POST['price']);

//       $loginError = $db->create_or_update_product($product, $price);
//     }
//   }

//   $select_stmt=$this->db()->prepare("SELECT * FROM product");
//   $select_stmt->execute();
//   var_dump($select_stmt);
//   while($row=$select_stmt->fecth(PDO::FETCH_ASSOC))
//   {

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FlowerPower</title>

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="style.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-default navbar-inverse" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="welcome_user.php">
                    <img src="vectorpaint.svg" alt="FlowerPower Logo" width="80" height="80">
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <p class="nav navbar-text">FlowerPower</p>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle"
                            data-toggle="dropdown"><b><?php echo "Welcome " . htmlentities( $_SESSION['username']) ."!" ?></b>
                            <span class="caret"></span></a>
                        <ul id="login-dp" class="dropdown-menu">
                            <li>
                                <div class="form-group">
                                    <a href="logout.php" class="btn btn-primary btn-block">Logout</a>
                                </div>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        </div>
    </nav>

    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-2" id="styling">
                <br>
                <h4 class="menu">Menu</h4>
                <br />
                <a href="welcome_emp.php">home</a><br />
                <br />
                <a href="overzicht_artikelen.php">Artikelen</a><br />
                <br />
                <a href="overzicht_medewerker.php">Medewerkers</a><br />
                <br />
                <a href="overzicht_bestellingen.php">Bestellingen</a><br />
            </div>
        </div>
    </div>

    <?php
    // $db = new db("localhost", "root", "flowerpower", "");
    $result_set = $db->show_profile_details_product("SELECT * FROM product ORDER BY id ASC", []);

    $columns = array_keys($result_set[0]); 

    $row_data = array_values($result_set);
    

    echo "<table>";
            // table row
            echo "<tr>";
                // loop all available columns, and store them in the top of the table (bold)
                foreach($columns as $column){
                    // table header
                    
                    echo "<th><strong> $column </strong></th>";
                    
                }
            echo "</tr>";

            // table rows. this part contains the data which will be shown in the table
            echo "<tr>";
                foreach($row_data as $value){

                    foreach($value as $data){
                        echo "<td>$data</td>";
                    }
                }
            echo "</tr>";
        echo "</table>"
    ?>

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>