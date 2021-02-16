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

        $fields = [
            'product', 'price'
        ];

        $obj = new Helper();
    
        $fields_validated = $obj->field_validation($fields);
    
        if($fields_validated){
            
            $product = trim(strtolower($_POST['product']));
            $price = trim(strtolower($_POST['price']));
    
            
                $db = new db('localhost', 'root', 'flowerpower', '');
    
                $msg = $db->sign_up_product($product, $price);
            
        }else{
            $missingFieldError = "Input for one of more fields missing. Please provide all required values and try again.";
        }
    }

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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b><?php echo "Welcome " . htmlentities( $_SESSION['username']) ."!" ?></b> <span
                                class="caret"></span></a>
                        <ul id="login-dp" class="dropdown-menu">
                            <li>
                                    <div class="form-group">
                                        <a href="logout.php" class="btn btn-primary btn-block">Logout</a>
                                    </div>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
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

    <div class="container">
  <div class="cheader">
    <div class="cheader">
      <h2>Create a Product</h2>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="form-group">
          <label for="name">Productname</label>
          <input type="text" name="product"class="form-control" value="<?php echo isset($_POST["product"]) ? htmlentities($_POST["product"]) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="text">Price</label>
          <input type="text" name="price" class="form-control"value="<?php echo isset($_POST["price"]) ? htmlentities($_POST["price"]) : ''; ?>" required /><br>
          <span>
            <?php 
                    echo ((isset($msg) && $msg != '') ? htmlentities($msg) ." <br>" : '');
                    echo ((isset($pwdError) && $pwdError != '') ? htmlentities($pwdError) ." <br>" : '')
                ?>
        </span>

        <input type="submit" class="form-control" name="submit" value="Add Product" />
        <span><?php echo ((isset($missingFieldError) && $missingFieldError != '') ? htmlentities($missingFieldError) : '')?></span>
    </form>
    </div>
  </div>
</div>

    
    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>