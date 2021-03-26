<?php

    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header('location: index.php');
        exit;
        }   
    
        include 'db.php';
        include 'validation.php';
    
        $db = new db("localhost", "root", "flowerpower", "");

        if(!empty($_GET["action"])) {
            switch($_GET["action"]) {
        case "add":
            if(!empty($_POST["quantity"])) {
                $productByCode = $db->select("SELECT * FROM product WHERE code='" . $_GET["code"] . "'", ['code'=>$_GET['code']]);
                $itemArray = array($productByCode[0]["code"]=>array('product'=>$productByCode[0]["product"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
                
                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
                        foreach($_SESSION["cart_item"] as $k => $v) {
                                if($productByCode[0]["code"] == $k) {
                                    if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                                        $_SESSION["cart_item"][$k]["quantity"] = 0;
                                    }
                                    $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                                }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);				
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                    }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
             break;
        }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // $product_id = (int)$_POST['productinfo'];
            // $product = $db->select("SELECT * FROM product WHERE id =:id", ['id'=>$product_id]);
            echo "test";
            
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

    <div id="shopping-cart">
        <div class="txt-heading">Shopping Cart</div>
        <a id="btnEmpty" href="artikelen_bestellen.php?action=empty">Empty Cart</a>
        <?php
            if(isset($_SESSION["cart_item"])){
                $total_quantity = 0;
                $total_price = 0;
        ?>
        <table class="tbl-cart" cellpadding="10" cellspacing="1">
            <tbody>
                <tr>
                    <th style="text-align:left;">Name</th>
                    <th style="text-align:left;">Code</th>
                    <th style="text-align:right;" width="5%">Quantity</th>
                    <th style="text-align:right;" width="10%">Unit Price</th>
                    <th style="text-align:right;" width="10%">Price</th>
                    <th style="text-align:center;" width="5%">Remove</th>
                    <th style="text-align:center;" width="5%">Order</th>
                </tr>
            <form action="artikelen_bestellen.php" method="post">
                <?php		
            foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
                <tr>
                    <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><br /><?php echo $item["product"]; ?>
                    </td>
                    <td><?php echo $item["code"]; ?></td>
                    <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                    <td style="text-align:right;"><?php echo "€ ".$item["price"]; ?></td>
                    <td style="text-align:right;"><?php echo "€ ". number_format($item_price,2); ?></td>
                    <td style="text-align:center;"><a href="artikelen_bestellen.php?action=remove&code=<?php echo $item["code"]; ?>"
                            class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
                    <td style="text-align:center;"></td>
                </tr>
                <?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?> 
            </form>

                <tr>
                    <td colspan="2" text-align="right">Total:</td>
                    <td text-align="right"><?php echo $total_quantity; ?></td>
                    <td text-align="right" colspan="2"><strong><?php echo "€ ".number_format($total_price, 2); ?></strong>
                    </td>
                    <td></td>
                    <td text-align="right"><input class="send" type="submit"></td>
                </tr>
            </tbody>
        </table>
        <?php
            } else {
        ?>
            <div class="no-records">Your Cart is Empty</div>
        <?php 
        }
        ?>
    </div>

    <div id="product-grid">
    <div class="txt-heading">Products</div>
    <?php
    $product_array = $db->show_producten("SELECT * FROM product");
    if (!empty($product_array)) { 
        foreach($product_array as $key=>$value){
    ?>
    <div class="col-sm-4"><br />
        <form method="post"
            action="artikelen_bestellen.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
            <div class="product-tile-footer">
                <div class="product-image"><img class="imgsize" src="<?php echo $product_array[$key]["image"]; ?>">
                </div>
                <div class="product-title"><?php echo $product_array[$key]["product"]; ?></div>
                <div class="product-price"><?php echo "€".$product_array[$key]["price"]; ?></div>
                <div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1"
                        size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
            </div>
        </form><br /><br />
    </div>
    <?php 
        }  
    } 
    ?>

    <footer class="page-footer font-small blue">
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="http://localhost/Flowerpower/"> FlowerPower</a>
        </div>
    </footer>

</body>

</html>