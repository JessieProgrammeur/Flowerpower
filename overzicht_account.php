<?php

    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header('location: index.php');
        exit;
        }   
    
        include 'db.php';
        include 'validation.php';
    
        $db = new db("localhost", "root", "flowerpower", "");
        
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

    <?php

    $result_set = $db->select("SELECT * FROM customer", []);
    $columns = array_keys($result_set[0]);
        
    $userinfo = $db->select("SELECT * FROM customer WHERE username = ?", [$username = $_SESSION['username']]);
    ?>

     <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h2>My account information</h2>
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