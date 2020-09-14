<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
$id = $_GET['id'];
if (!empty($id)) {
    $smarty->assign ("done", 1);
    $query  ="SELECT `code` FROM `ads` where `id` = '$id' ";
    $row    = $db->rawQuery($query);
    $fetch  = $row[0];
    $smarty->assign ("frame_code", $fetch['code']);
} else {
    $smarty->assign ("done", 0);
}
$templatefile = 'showads';
$smarty->display("default/showads.html");
