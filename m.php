<?php
require 'includes/config.php';
require 'smarty.php';
$id     = $_GET['id'];
$query  ="SELECT * FROM `links` where `id` = '$id' ";
$row    = $db->rawQuery($query);
if ($db->count > 0) {
    $fetch  = $row[0];
    $smarty->assign ("url",       $fetch['url']);
    $smarty->assign ("rand",      $id);
    $smarty->assign ("name",      $fetch['name']);
    $smarty->assign ("size",      $fetch['size']);
    $smarty->assign ("date",      $fetch['date']);
    $smarty->assign ("downloads", $fetch['downloads']);
    $smarty->assign ("author",    $fetch['author']);
    $smarty->assign ("id",        $id);
} else {
    header("location: index.php");
    die();
}


$templatefile = 'm';
require 'display.php';


