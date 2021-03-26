<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
header('location: index.php');
exit;
}   

include 'db.php';
include 'validation.php';

$db = new db("localhost", "root", "flowerpower", "");

if(isset($_GET['id'])) {
  $db->update_or_delete_order("DELETE FROM orders  WHERE id=:id", ['id'=>$_GET['id']]);
        $loginError = $db->update_or_delete_order($sql, $placeholder);
        var_dump($loginError);
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
            <div class="col-2" id="styleuseruser">
                <br>
                <h4 class="menu">Menu</h4>
                <br />
                <a href="welcome_user.php">home</a><br />
                <br />
                <a href="artikelen_bestellen.php">producten</a><br />
                <br />
                <a href="overzicht_account.php">Account</a><br />
                <br />
                <a href="overzicht_factuur.php">Facturen</a><br />
                <br />
            </div>
        </div>
    </div>
    
    <div>
        <a class="btproduct" href='create_order.php' type="button">Click here to add a order</a>
    </div>

    <?php

      $result_set = $db->select("SELECT orders.id, product.product, product.price, product.code, product.image, 
      invoiceline.amount, invoiceline.price, 
      customer.initials, customer.prefix, customer.last_name, customer.address, customer.postal_code, customer.residence, customer.email, customer.username, 
      store.name, store.address, store.postal_code, store.residence, store.phone_number 
      FROM orders, product, invoiceline, customer, store ", []);
      $columns = array_keys($result_set[0]);

      $userinfo = $db->select("SELECT orders.id, product.product, product.price, product.code, product.image, 
        invoiceline.amount, invoiceline.price, 
        customer.initials, customer.prefix, customer.last_name, customer.address, 
        customer.postal_code, customer.residence, customer.email, customer.username, 
        store.name, store.address, store.postal_code, store.residence, store.phone_number 
        FROM orders 
        LEFT JOIN product ON orders.id = product.id
        LEFT JOIN invoiceline ON product.id = invoiceline.id 
        LEFT JOIN store ON invoiceline.id = store.id 
        LEFT JOIN customer ON store.id = customer.id WHERE username = ?", [$username = $_SESSION['username']]);

      // [$username = $_SESSION['username']]);
    ?>

     <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h2>My invoices</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php foreach($columns as $column){ ?>
                            <th>
                                <strong> <?php echo $column ?> </strong>
                            </th>
                            <?php } ?>
                            <th colspan="2">action</th>
                        </tr>
                    </thead>
                    <?php foreach($userinfo as $rows => $row){ ?>

                    <?php $row_id = $row['id']; ?>
                    <tr>
                        <?php   foreach($row as $row_data){?>
                        <td>
                            <?php echo $row_data ?>
                        </td>
                        
                        <?php } ?><td>
                            <a href="edit_customer_user.php?id=<?= $result_set->id ?>" class="btn btn-info">Edit</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
    
    
    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/welcome_user.php"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>