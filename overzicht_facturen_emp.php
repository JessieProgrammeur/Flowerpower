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
  $db->update_or_delete_invoice("DELETE FROM invoice WHERE id=:id", ['id'=>$_GET['id']]);
        $loginError = $db->update_or_delete_invoice($sql, $placeholder);
        var_dump($loginError);
}

if(isset($_POST['export'])){
    $filename = "invoices_data_export.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $print_header = false;
    
    $result = $db->get_invoice_information();
    
    if(!empty($result)){
        foreach($result as $row){
            if(!$print_header){
                echo implode("\t", array_keys($row)) ."\n";
                $print_header=true;
            }
            echo implode("\t", array_values($row)) ."\n";
        }
    }
    exit;
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
                <a href="welcome_emp.php">
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
            <div class="col-2" id="homemenu3">
                <br>
                <h4 class="menu">Menu</h4>
                <br />
                <a href="welcome_emp.php">home</a><br />
                <br />
                <a href="overzicht_artikelen.php">Artikelen</a><br />
                <br />
                <a href="overzicht_medewerker.php">Medewerkers</a><br />
                <br />
                <a href="overzicht_users.php">Gebruikers</a><br />
                <br />
                <a href="overzicht_bestellingen.php">Bestellingen</a><br />
                <br />
                <a href="overzicht_facturen_emp.php">Facturen</a><br />
            </div>
        </div>
    </div>
    
    <div>
        <a class="btproduct" href='artikelen_bestellen.php' type="button">Click here to add a order</a>
    </div>
    <form method="post" action="overzicht_facturen_emp.php" class="row">
        <div class="col-6"></div>
        <div class="col-6"><input type="submit" value="Export" name="export" class="btproduct" /></div>
    </form>
    
    <?php
    // $db = new db("localhost", "root", "flowerpower", "");
    $result_set = $db->select("SELECT orders.id, product.product, product.price, product.code, product.image, 
    invoiceline.amount, invoiceline.price, 
    customer.initials, customer.prefix, customer.last_name, customer.address, customer.postal_code, customer.residence, customer.email, customer.username,
    store.name, store.address, store.postal_code, store.residence, store.phone_number 
    FROM orders, product, invoiceline, customer, store", []);
    $columns = array_keys($result_set[0]);

    $factuur = $db->select("SELECT orders.id, product.product, product.price, product.code, product.image, 
    invoiceline.amount, invoiceline.price, 
    customer.initials, customer.prefix, customer.last_name, customer.address, 
    customer.postal_code, customer.residence, customer.email, customer.username, 
    store.name, store.address, store.postal_code, store.residence, store.phone_number 
    FROM orders 
    LEFT JOIN product ON orders.id = product.id
    LEFT JOIN invoiceline ON product.id = invoiceline.id 
    LEFT JOIN store ON invoiceline.id = store.id 
    LEFT JOIN customer ON store.id = customer.id", []);

  ?>
     <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h2>All Invoices</h2>
            </div>
            <div class="card-body">
                <table>
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
                    <?php foreach($factuur as $rows => $row){ ?>

                    <?php $row_id = $row['id']; ?>
                    <tr>
                        <?php   foreach($row as $row_data){?>
                        <td>
                            <?php echo $row_data ?>
                        </td>
                        <?php } ?><td>
                            <a href="edit_invoice_emp.php?<?= $result_set->id ?>" class="btn btn-info">Edit</a>
                            <a onclick="return confirm('Are you sure you want to delete this entry?')"
                                href="overzicht_facturen_emp.php?id=<?= $result_set->id ?>"
                                class='btn btn-danger'>Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
   
    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/welcome_emp.php"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>