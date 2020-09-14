<?php
//include smarty files//
include '../includes/functions.php';
include_once '../api/smarty/libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->caching = 0;
$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty = new Smarty ();
$query  = "SELECT * FROM `config` where id= 1 ";
$rows   = $db->rawQuery($query);
$fetch  = $rows[0];
$smarty->assign ("sitename",    $fetch['sitename']);
$smarty->assign ("sitetitle",   $fetch['sitetitle']);
$smarty->assign ("siteurl",     $fetch['siteurl']);
$smarty->assign ("template",    $fetch['template']);
$url    = parse_url($fetch['siteurl']);
$domain = $url["host"]; //This variable contains the fragment
// check if install folder exists
if (file_exists('../install')){
    $install_exists = 1;
    $smarty->assign ("install_exists", $install_exists);
}