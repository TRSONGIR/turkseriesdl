<?php
require 'includes/config.php';
require 'smarty.php';
$link_id = $_POST['id'];
$query = "UPDATE `links` SET `downloads` = `downloads`+1 WHERE `id` = $link_id ";
$row   = $db->rawQuery($query);
// for debuging
//file_put_contents('counter.txt', $link_id);