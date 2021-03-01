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
                <a href="overons.php">over ons</a><br />
                <br />
                <a href="contact.php">contact</a><br />
                <br>
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
    // $db = new db("localhost", "root", "flowerpower", "");
    $result_set = $db->show_profile_details_invoice(
    "SELECT product_product,
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
    ORDER BY product_product, product_price");
  ?>
    <div class="container">
  <div class="card mt-5">
    <div class="card-header">
      <h2>My Invoices</h2>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <th>Productname</th>
          <th>Price</th>
          <th>Amount</th>
          
          <th>Store Name</th>
          <th>Store address</th>
          <th>Store Postal Code</th>
          <th>Store Residence</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
        <?php foreach($result_set as $result): ?>
          <tr>
            <td><?= $result->id; ?></td>
            <td><?= $result->product_product; ?></td>
            <td><?= $result->product_price; ?></td>
            <td><?= $result->invoiceline_amount; ?></td>
            
            <td><?= $result->store_name; ?></td>
            <td><?= $result->store_address; ?></td>
            <td><?= $result->store_postal_code; ?></td>
            <td><?= $result->store_residence; ?></td>
            <td><?= $result->invoice_date; ?></td>
            <td>
              <a onclick="return confirm('Are you sure you want to delete this entry?')"
                  href="overzicht_factuur.php?id=<?= $result->id ?>" class='btn btn-danger'>Delete</a>
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