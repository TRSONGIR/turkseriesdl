<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
// check if the user is admin
$id = $loggedInUser->user_id;
if (!user_is_admin($id)){die("you don't have access permission");}
