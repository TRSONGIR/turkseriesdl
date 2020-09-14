<?php
require 'includes/config.php';
require 'smarty.php';
$id     = $_GET['id'];
$query  ="SELECT * FROM `links` where `id` = '$id' ";
$row    = $db->rawQuery($query);
$fetch  = $row[0];
if ($db->count > 0) {
    $addhttp_url = addhttp($fetch['url']);
    $smarty->assign ("url",       $addhttp_url); //add http:// if it's not exists in the URL?
    $smarty->assign ("rand",      $id);
    $smarty->assign ("name",      $fetch['name']);
    $smarty->assign ("size",      $fetch['size']);
    $smarty->assign ("date",      $fetch['date']);
    $smarty->assign ("downloads", $fetch['downloads']);
    $smarty->assign ("author",    $fetch['author']);
    $smarty->assign ("id",        $id);
    // the counter will be updated from counter.php
} else {
    header("location: index.php");
    die();
}
$templatefile = 'm1';
require 'display.php';
