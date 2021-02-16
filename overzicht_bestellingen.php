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
                <a href="Welcome_emp.php">home</a><br />
                <br />
                <a href="overzicht_artikelen.php">Artikelen</a><br />
                <br />
                <a href="overzicht_medewerker.php">Medewerkers</a><br />
                <br />
                <a href="overzicht_bestellingen.php">Bestellingen</a><br />
            </div>
        </div>
    </div>

    <div>
        <a class="btproduct" href='create_order.php' type="button">Click here to add a order</a>
    </div>
    
    <?php
    // $db = new db("localhost", "root", "flowerpower", "");
    $result_set = $db->show_profile_details_order("SELECT * FROM product");
  ?>
    <div class="container">
  <div class="card mt-5">
    <div class="card-header">
      <h2>All Orders</h2>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <th>Product id</th>
          <th>Store id</th>
          <th>Amount</th>
          <th>customer id</th>
          <th>employee id</th>
          <th>picked-up</th>
          <th>created at</th>
          <th>updated at</th>
          <th>Actions</th>
        </tr>
        <?php foreach($result_set as $result): ?>
          <tr>
            <td><?= $result->id; ?></td>
            <td><?= $result->product_id; ?></td>
            <td><?= $result->store_id; ?></td>
            <td><?= $result->amount; ?></td>
            <td><?= $result->customer_id; ?></td>
            <td><?= $result->employee_id; ?></td>
            <td><?= $result->picked_up; ?></td>
            <td><?= $result->created_at; ?></td>
            <td><?= $result->updated_at; ?></td>
            <td>
              <a href="edit.php?id=<?= $result->id ?>" class="btn btn-info">Edit</a>
              <a onclick="return confirm('Are you sure you want to delete this entry?')" href="delete.php?id=<?= $result->id ?>" class='btn btn-danger'>Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
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