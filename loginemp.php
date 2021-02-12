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

      $loginError = $db->loginemp($username, $password);
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
            </div>
        </div>
    </nav>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-2" id="homemenu2">
                <br>
                <h4 class="menu">Menu</h4>
                <br />
            </div>
        </div>
    </div>
  
    <ul id="login-dp2">
        <div class="row">
                <div class="col-md-12">
                    <form action="loginemp.php" method="post">
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
    </ul>


    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>