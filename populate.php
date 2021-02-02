<?php

include 'db.php';

$db = new db("localhost", "root", "flowerpower", "");

// aanmaken admin
$db->sign_upemp('admin', $db::ADMIN, 'J', NULL, 'Algra', 'admin');

//aanmaken store
// $db->('');

// todo: aanmaken product
// $db->add_prd('Boeket', '10',);

//aanmaken order
// $db->create_order_customer('amount');

?>