<?php

    include 'db.php';
    include 'validation.php';

    $db = new db("localhost", "root", "flowerpower", "");
    
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){
    $fields = ['username', 'password'];

    $obj = new Helper();

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
                <a href="artikelen_bestellen.php">
                    <img src="vectorpaint.svg" alt="FlowerPower Logo" width="80" height="80">
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <p class="nav navbar-text">FlowerPower</p>
                <ul class="nav navbar-nav navbar-right">
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-2" id="styleuseruser">
                <br>
                <h4 class="menu">Menu</h4>
                <br />
                <a href="welcome_user.php">home</a><br />
                <br />
                <a href="artikelen_bestellen.php">producten</a><br />
                <br />
                <a href="overons.php">over ons</a><br />
                <br />
                <a href="contact.php">contact</a><br />
                <br>
                <a href="overzicht_medewerker.php">Account</a><br />
                <br />
                <a href="overzicht_bestellingen.php">Bestellingen</a><br />
                <br />
            </div>
        </div>
    </div>

    <?php
    $result_set = $db->show_details_product("SELECT product, price FROM product ORDER BY id ASC", []);

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
        echo "</table>";
    ?>

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>