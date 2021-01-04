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
                                        Login
                                        <div class="form-group">
                                            <label for="Username">Username :</label>
                                            <input class="form-control" type="text" id="username" name="username"
                                                required>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="Password">Password :</label>
                                            <input class="form-control" type="password" id="password" name="password"
                                                required>
                                        </div>
                                        <div class="help-block text-right"><a href="">Forget the password ?</a>
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

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>
    
</body>

</html>