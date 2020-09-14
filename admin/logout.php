<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Log the user out
if(isUserLoggedIn())
{
	$loggedInUser->userLogOut();
}
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
// end delete all path cookies
require '../includes/config.php';
require 'smarty.php';
// check refere to protect admin from CSRF attacks
check_ref ($domain);
$templatefile = 'logout';
$smarty->display("default/logout.html");
