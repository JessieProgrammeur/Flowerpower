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
            'store_id', 'amount', 'customer_id', 'employee_id'
        ];
        var_dump($fields);

        $obj = new Helper();
        
        $fields_validated = $obj->field_validation($fields);
    
        if($fields_validated){
            $store_id = trim(strtolower($_POST['store_id']));
            $amount = trim(strtolower($_POST['amount']));
            $customer_id = trim(strtolower($_POST['customer_id']));
            $employee_id = trim(strtolower($_POST['employee_id']));
    
            
                $db = new db('localhost', 'root', 'flowerpower', '');
    
                $msg = $db->sign_up_order($store_id, $amount, $customer_id, $employee_id);
            
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
      <h2>Create Order</h2>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="form-group">
          <label for="name">store id</label>
          <input type="text" name="store_id"class="form-control" value="<?php echo isset($_POST["store_id"]) ? htmlentities($_POST["store_id"]) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="name">amount</label>
          <input type="text" name="amount"class="form-control" value="<?php echo isset($_POST["amount"]) ? htmlentities($_POST["amount"]) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="name">customer id</label>
          <input type="text" name="customer_id"class="form-control" value="<?php echo isset($_POST["customer_id"]) ? htmlentities($_POST["customer_id"]) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="text">employee id</label>
          <input type="text" name="employee_id" class="form-control"value="<?php echo isset($_POST["employee_id"]) ? htmlentities($_POST["employee_id"]) : ''; ?>" required /><br>
          <span>
            <?php 
                    echo ((isset($msg) && $msg != '') ? htmlentities($msg) ." <br>" : '');
                    echo ((isset($pwdError) && $pwdError != '') ? htmlentities($pwdError) ." <br>" : '')
                ?>
        </span>

        <input type="submit" class="form-control" name="submit" value="Add Order" />
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