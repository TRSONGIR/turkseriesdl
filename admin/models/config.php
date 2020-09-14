<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/
require_once("db-settings.php"); //Require DB connection

//Retrieve settings
$query  = "SELECT * FROM `config` where id= 1 ";
$rows   = $db->rawQuery($query);
$fetch  = $rows[0];
//Set Settings
$emailActivation = $fetch['activation'];
$mail_templates_dir = "models/mail-templates/";
$websiteName = $fetch['sitename'];
$websiteUrl = $fetch['siteurl'];
$emailAddress = $fetch['email'];
$resend_activation_threshold = $fetch['resend_activation_threshold'];
$emailDate = date('dmy');
$language = $fetch['language'];
$template = $fetch['template'];

$master_account = -1;

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
$default_replace = array($websiteName,$websiteUrl,$emailDate);

if (!file_exists($language)) {
	$language = "models/languages/en.php";
}

if(!isset($language)) $language = "models/languages/en.php";

//Pages to require
require_once($language);
require_once("class.mail.php");
require_once("class.user.php");
require_once("class.newuser.php");
require_once("funcs.php");

session_start();

//Global User Object Var
//loggedInUser can be used globally if constructed
if(isset($_SESSION["userCakeUser"]) && is_object($_SESSION["userCakeUser"]))
{
	$loggedInUser = $_SESSION["userCakeUser"];
}

?>
