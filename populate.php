<?php

include 'db.php';

$db = new database('localhost', 'root', '', 'flowerpower', 'utf8');
$db->sign_up('admin', $db::ADMIN, 'jessie', NULL, 'programmeur', 'j.p@admin.com', 'admin');

?>