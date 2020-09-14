<?php
require_once("models/config.php");
if (!isUserLoggedIn()) {
    require '../includes/config.php';
    require 'smarty.php';
    $smarty->display("default/login_falid.html");
} else {
    header("location:index.php");
    die();
}
