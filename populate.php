<?php

include 'database.php';
$db = new database('localhost', 'root', '', 'flowerpower', 'utf8');

// call the sign_up method from the database class
$db->sign_up('admin', $db::ADMIN, 'jessie', NULL, 'programmeur', 'j.p@admin.com', 'admin');

?>