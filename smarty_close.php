<?php
//include smarty files//
include_once './api/smarty/libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->caching = 0;
$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$copyright = '<font face="tahoma" style="font-size: 10pt">By <a href="http://www.abdulibrahim.com/" target="_blank">abduli brahim</a></font>';
$smarty->assign ('copyright', $copyright);
$query       = "SELECT * FROM `config` where id=1 ";
$configs     = $db->rawQuery($query);
$all_configs = $configs[0];
$time        = $all_configs['time'];
$url         = parse_url($all_configs['siteurl']);
$domain = $url["host"]; //This variable contains the fragment
$smarty->assign ("domain",      $domain);
$smarty->assign ("sitename",    $all_configs['sitename']);
$smarty->assign ("sitetitle",   $all_configs['sitetitle']);
$smarty->assign ("siteurl",     $all_configs['siteurl']);
$smarty->assign ("keywords",    $all_configs['keywords']);
$smarty->assign ("time",        $time);
$smarty->assign ("email",       $all_configs['email']);
$smarty->assign ("template",    $all_configs['template']);
$smarty->assign ("description", $all_configs['description']);  // by abdul
$smarty->assign ("analytics",   $all_configs['analytics']);   // by abdul
$template   = $all_configs["template"];
$sitemsg    = $all_configs["sitestatemsg"];
$sitestate  = $all_configs["sitestate"];

