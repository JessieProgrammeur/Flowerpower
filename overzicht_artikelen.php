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
        $coc = $db->create_order_customer($amount);

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
            <div class="col-2" id="sidenavoa">
                <br>
                <h4 class="menu">Menu</h4>
                <br />
                <a href="index.php">home</a><br />
                <br />
                <a href="overzicht_artikelen.php">producten</a><br />
                <br />
                <a href="diensten.php">diensten</a><br />
                <br />
                <a href="overons.php">over ons</a><br />
                <br />
                <a href="contact.php">contact</a><br />
                <br>
                <a href="overzicht_medewerker.php">Account</a><br />
                <br />
                <a href="mijn_bestellingen.php">Bestellingen</a><br />
                <br />
                <a href="artikelen_bestellen.php">Winkelwagen</a><br />
                <br />
            </div>
        </div>
    </div>

    <div class="container1" style="padding-left: 100px">

        <form method="post" action="overzicht_artikelen.php?">

            <div class="product">
                <img src="rrozen.jpg" class="afb">
                <h5 class="text-prd">boeket</h5>
                <h5 class="text-prd">Rode Rozen</h5>
                <input type="text" name="quantity" class="form-control" value="1<?php echo isset($_POST["amount"]) ? htmlentities($_POST["amount"]) : ''; ?>" required />
                <input type="hidden" name="hidden_price">
                <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart">
        </form>
    </div>

    <div class="container1">

        <form method="post" action="overzicht_artikelen.php?">

            <div class="product">
                <img src="boeket2.jpg" class="afb">
                <h5 class="text-prd">boeket</h5>
                <h5 class="text-prd">Favoriet</h5>
                <input type="text" name="quantity" class="form-control" value="1">
                <input type="hidden" name="hidden_price"
                    value="<?php echo isset($_POST["amount"]) ? htmlentities($_POST["amount"]) : ''; ?>" required />
                <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart">
            </div>
        </form>
    </div>

    <div class="container1">

        <form method="post" action="overzicht_artikelen.php?">

            <div class="product">
                <img src="boeket3.jpg" class="afb">
                <h5 class="text-prd">boeket</h5>
                <h5 class="text-prd">Aanbieding</h5>
                <input type="text" name="quantity" class="form-control" value="1">
                <input type="hidden" name="hidden_price"
                    value="<?php echo isset($_POST["amount"]) ? htmlentities($_POST["amount"]) : ''; ?>" required />
                <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart">
            </div>
        </form>
    </div>
    <?php
//   }
  ?>

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>