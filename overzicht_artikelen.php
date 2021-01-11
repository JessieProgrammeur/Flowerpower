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
            </div>
        </div>
    </div>
<!-- <div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <a href="#"><img class="card-img-top" src="images/<?php echo $row['product_image']; ?>" width="400px" height="200px"></a>

            <div class="card-body">
                <h4 class="card-title text-primary"><?php echo $row['product_product']; ?> </h4>
                <h5><?php echo number_format($row['product_price'], 2); ?>/-</h5>
            </div>

            <div class="card-footer">
                <form class="form-submit">
                    <input type="hidden" class="pid" value="<?php echo $row['product_id']; ?>">
                    <input type="hidden" class="pproduct" value="<?php echo $row['product_product']; ?>">
                    <input type="hidden" class="pprice" value="<?php echo $row['product_price']; ?>">
                    <input type="hidden" class="pcreated_at" value="<?php echo $row['product_created_at']; ?>">
                    <input type="hidden" class="pupdated_at" value="<?php echo $row['product_updated_at']; ?>">
                    <button id="addItem" class="btn btn-succes btn-md">Toevoegen aan winkelwagen</button>
                </form>
            </div>
        </div>
    </div>
<?php
//   }
?>
</div> -->

    <div class="container">
        <div class="card-group">

            <div class="row">
                <div class="column">
                    <img class="card-img-top" src="boeket2.jpg">
                    <div class="card-body">
                        <h3 class="card-title">All time favorite</h3>
                        <p class="card-text align-center"></p>
                    </div>
                </div>

                <div class="column">
                    <img class="card-img-top" src="rrozen.jpg">
                    <div class="card-body">
                        <h3 class="card-title">Boeket Rode Rozen</h3>
                        <p class="card-text"></p>
                    </div>
                </div>

                <div class="column">
                    <img class="card-img-top" src="boeket3.jpg">
                    <div class="card-body">
                        <h3 class="card-title">Boeket van de maand</h3>
                        <p class="card-text"></p>
                    </div>
                </div>
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