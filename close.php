<?php
require 'includes/config.php';
require 'smarty_close.php';
if ($sitestate == 0) {
    $smarty->assign ("sitemsg", $sitemsg);
} else {
    header("location: index.php");
    die();
}
$templatefile = 'close';
require 'display.php';

