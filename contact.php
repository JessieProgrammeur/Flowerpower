<?php

    session_start();

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
                <a href="welcome_user.php">
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

                                        <form action="contact.php" method="post">

                                            <div class="form-group">
                                                <label for="Username">Username :</label>
                                                <input class="form-control" type="text" name="username" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="Password">Password :</label>
                                                <input class="form-control" type="password" name="password" required>
                                            </div>
                                    </div>

                                    <span><?php echo ((isset($loginError) && $loginError != '') ? $loginError ."<br>" : '')?></span>

                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block" value="Login">Sign in</button>
                                    </div>

                                    </form>
                                </div>

                                <div class="bottom text-center">
                                    Nieuw hier? <a href="signup.php"><b>meld je hier aan</b></a>
                                </div>
                                <div class="bottom text-center">
                                    <a href="loginemp.php"><b>Inloggen Medewerker</b></a>
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
                <a href="overons.php">over ons</a><br />
                <br />
                <a href="contact.php">contact</a><br />
                <br />
            </div>
        </div>
    </div>

    <div class="container"> 
        <div class="card-group"> 
            <div class="row"> 
                <div class="column"> 
                    <img class="card-img-top" src="boetiek2.jpg"> 
                    <div class="card-body"> 
                        <h3 class="card-title">Onze nieuwste flowershop</h3> 
                        <p class="card-text align-center">Singel 10, 3000AM Utrecht, 0624681011</p> 
                    </div> 
                </div> 
                <div class="column"> 
                    <img class="card-img-top" src="boetiek3.jpg"> 
                    <div class="card-body"> 
                        <h3 class="card-title">De flowershop Rotterdam</h3> 
                        <p class="card-text">Het plein 2, 2000AM Rotterdam, 06987654321</p> 
                    </div> 
                </div> 
                <div class="column"> 
                    <img class="card-img-top" src="botiek5.jpg"> 
                    <div class="card-body"> 
                        <h3 class="card-title">De Flowershop Amsterdam</h3> 
                        <p class="card-text">De Dam 2, 1000AM Amsterdam, 06123456789</p> 
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