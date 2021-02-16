<?php

include 'db.php';

$db = new db("localhost", "root", "flowerpower", "");

// aanmaken admin

$db->sign_upemp('admin', $db::ADMIN, 'J', NULL, 'Algra', 'admin');
?>