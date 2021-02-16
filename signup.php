<?php

include 'db.php';
include 'validation.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){

    $fields = [
        'initials', 'prefix', 'lastname', 'address', 'postalcode', 'residence', 'birthdate', 'email', 'username', 'password', 'cpwd'
    ];

    $obj = new Helper();

    $fields_validated = $obj->field_validation($fields);

    if($fields_validated){
        
        $initials = trim(strtolower($_POST['initials']));
        $lastname = trim(strtolower($_POST['lastname']));
        $prefix = isset($_POST['prefix']) ? trim(strtolower($_POST['prefix'])) : NULL;
        $address = trim(strtolower($_POST['address']));
        $postalcode = trim(strtolower($_POST['postalcode']));
        $residence = trim(strtolower($_POST['residence']));
        $birthdate = trim(strtolower($_POST['birthdate']));
        $email = trim(strtolower($_POST['email']));
        $username = trim(strtolower($_POST['username']));
        $password = trim(strtolower($_POST['password']));
        $cpwd = trim(strtolower($_POST['cpwd']));

        if($password !== $cpwd){
            $pwdError = "Passwords do not match. Please fix your input errors and try again.";
        }else{
            $db = new db('localhost', 'root', 'flowerpower', '');

            $msg = $db->sign_up($username, $db::USER, $initials, $prefix, $lastname, $address, $postalcode, $residence, $birthdate, $email, $password);
        }
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
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

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
                <a href="index.php">
                    <img src="vectorpaint.svg" alt="FlowerPower Logo" width="80" height="80">
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <p class="nav navbar-text">FlowerPower</p>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span
                                class="caret"></span></a>
                        <ul id="login-dp" class="dropdown-menu">
                            <li>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="index.php" method="post" role="form">
                                            <div class="form-group">
                                                <label for="Username">Username :</label>
                                                <input class="form-control" type="text" id="username" name="username"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="Password">Password :</label>
                                                <input class="form-control" type="password" id="password"
                                                    name="password" required>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                                    </div>
                                    </form>
                                </div>

                                <div class="bottom text-center">
                                    Nieuw hier? <a href="newuser.php"><b>meld je hier aan</b></a>
                                </div>
                                <div class="help-block text-right"><a href="passr.php">Wachtwoord vergeten?</a>
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
            <div class="col-2" id="homemenu">
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
                <br />
            </div>
        </div>
    </div>

    <p class="py-0 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
    <form action="signup.php" method="post">
        <input type="text" class="form-control" name="initials" placeholder="Voorletters"
            value="<?php echo isset($_POST["fname"]) ? htmlentities($_POST["fname"]) : ''; ?>" required /><br>
        <input type="text" class="form-control" name="prefix" placeholder="Tussenvoegsel"
            value="<?php echo isset($_POST["prefix"]) ? htmlentities($_POST["prefx"]) : ''; ?>" /><br>
        <input type="text" class="form-control" name="lastname" placeholder="Achternaam"
            value="<?php echo isset($_POST["lastname"]) ? htmlentities($_POST["lastname"]) : ''; ?>" required /><br>
        <input type="text" class="form-control" name="address" placeholder="Adres"
            value="<?php echo isset($_POST["address"]) ? htmlentities($_POST["address"]) : ''; ?>" required /><br>
        <input type="text" class="form-control" name="postalcode" placeholder="Postcode"
            value="<?php echo isset($_POST["postalcode"]) ? htmlentities($_POST["postalcode"]) : ''; ?>" required /><br>
        <input type="text" class="form-control" name="residence" placeholder="Plaats"
            value="<?php echo isset($_POST["residence"]) ? htmlentities($_POST["residence"]) : ''; ?>" required /><br>
        <input type="text" class="form-control" name="birthdate" placeholder="Geboortedatum"
            value="<?php echo isset($_POST["birthdate"]) ? htmlentities($_POST["birthdate"]) : ''; ?>" required /><br>
        <input type="email" class="form-control" name="email" placeholder="Email"
            value="<?php echo isset($_POST["email"]) ? htmlentities($_POST["email"]) : ''; ?>" required /><br>
        <input type="text" class="form-control" name="username" placeholder="Gebruikersnaam"
            value="<?php echo isset($_POST["username"]) ? htmlentities($_POST["username"]) : ''; ?>" required /><br>
        <input type="password" class="form-control" name="password" placeholder="Wachtwoord" required /><br>
        <input type="password" class="form-control" name="cpwd" placeholder="Herhaal wachtwoord" required /><br>
        
        <span>
            <?php 
                    echo ((isset($msg) && $msg != '') ? htmlentities($msg) ." <br>" : '');
                    echo ((isset($pwdError) && $pwdError != '') ? htmlentities($pwdError) ." <br>" : '')
                ?>
        </span>

        <input type="submit" class="form-control" name="submit" value="Sign up!" />
        <span><?php echo ((isset($missingFieldError) && $missingFieldError != '') ? htmlentities($missingFieldError) : '')?></span>
    </form>

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>