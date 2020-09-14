<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Turn off all error reporting
error_reporting(0);
$is_demo = "1";  // 1 for yes and 0 for no
$dbhost  = "localhost";
$dbuser  = ""; // database user name
$dbpass  = ""; // database password
$dbname  = ""; // database name
if (empty($dbuser) || empty($dbname)) {
     die('connection to database error <br> please edit the includes/config.php <br> with the database details');
}
require_once('MysqliDb.php');
$db = new MysqliDb($dbhost, $dbuser, $dbpass, $dbname);
