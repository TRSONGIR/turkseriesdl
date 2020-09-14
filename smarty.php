<?php
//include smarty files//
include 'includes/functions.php';
include_once './api/smarty/libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->caching = 0;
$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$copyright   = '<font face="tahoma" style="font-size: 10pt">By <a href="http://www.abdulibrahim.com/" target="_blank">abduli brahim</a></font>';
$smarty->assign ('copyright', $copyright);
$smarty->assign ("is_demo", $is_demo);
$query       ="SELECT * FROM `config` where id=1 ";
$configs     = $db->rawQuery($query);
$all_configs = $configs[0];
$time        = $all_configs['time'];
$url         = parse_url($all_configs['siteurl']);
$domain      = $url["host"]; //This variable contains the fragment
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

$query  = "SELECT * FROM `ads`  where type = 'rectangle' && `template` = '$template' && `active` = '1'";
$rectangle  = $db->rawQuery($query);
// check if the ad is active 
if (count($rectangle) > 0) {
  $smarty->assign ("rectangle", $rectangle[0]['code']);  
}
// end rectangle
// squar ads
$sql    = "SELECT * FROM `ads`  where type = 'squar' && `template` = '$template' && `active` = '1'";
// check if the ad is active 
$squar  = $db->rawQuery($sql);
if (count($squar) > 0) {
  $smarty->assign ("squar", $squar[0]['code']);
}

// end squar
// social
$social = $social   = $db->rawQuery("SELECT * FROM `social` ORDER BY id ASC ");
$facebook_url       = (($social[0]['active'] == 1))     ? $social[0]['code'] : '#';                      
$google_plus_url    =    (($social[1]['active'] == 1))  ? $social[1]['code'] : '#'; ;
$twitter_url        =    (($social[2]['active'] == 1))  ? $social[2]['code'] : '#'; 
$dribbble_url       =    (($social[3]['active'] == 1))  ? $social[3]['code'] : '#'; 
$linkedin_url       =    (($social[4]['active'] == 1))  ? $social[4]['code'] : '#'; 
$smarty->assign ("facebook_url",     $facebook_url);
$smarty->assign ("twitter_url",      $twitter_url);
$smarty->assign ("google_plus_url",  $google_plus_url);
$smarty->assign ("dribbble_url",     $dribbble_url);
$smarty->assign ("linkedin_url",     $linkedin_url);
// for options table
$query_options  = "SELECT * FROM `options`";
$rows_options           = $db->rawQuery($query_options);
$link_window            = $rows_options[0]['option_value'];
$second_page            = $rows_options[1]['option_value'];
$recaptcha_status       = $rows_options[2]['option_value'];
$recaptcha_Site_key     = $rows_options[3]['option_value'];
$recaptcha_Secret_key   = $rows_options[4]['option_value'];

$smarty->assign ("link_window",         $link_window);
$smarty->assign ("second_page",         $second_page);
$smarty->assign ("recaptcha_status",   $recaptcha_status );
$smarty->assign ("recaptcha_Site_key",  $recaptcha_Site_key);
$smarty->assign ("recaptcha_Secret_key",$recaptcha_Secret_key);

if($sitestate == 0){
    header("location: close.php");
    die();
}
