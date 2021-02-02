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
    $fields = ['username', 'password'];

    $obj = new Validation();

    $fields_validated = $obj->field_validation($fields);

   if($fields_validated){
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);

      $loginError = $db->login($username, $password);
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
                <a href="index.php">home</a><br />
                <br />
                <a href="producten.php">producten</a><br />
                <br />
                <a href="diensten.php">diensten</a><br />
                <br />
                <a href="overons.php">over ons</a><br />
                <br />
                <a href="contact.php">contact</a><br />
                <br>
                <a href="user.php">Account</a><br />
                <br />
                <a href="mijn_bestellingen.php">Bestellingen</a><br />
                <br />
            </div>
        </div>
    </div>

    <?php 

echo "<table>";

$db = new db("localhost", "root", "flowerpower", "");
$result_set = $db->show_profile_details_user($_SESSION['username']);

$columns = array_keys($result_set); 

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
                    // table data
                    echo "<td>";
                    echo implode($value); 
                    echo "</td>";
                }
            echo "</tr>";
        echo "</table>"



//echo "<table>";
?>

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>