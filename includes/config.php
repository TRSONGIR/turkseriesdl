<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Turn off all error reporting
error_reporting(0);
$is_demo = "1";  // 1 for yes and 0 for no
$dbhost  = "sh4ob67ph9l80v61.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$dbuser  = "vz4scdlmp7qm1xg8"; // database user name
$dbpass  = "yy3jlvsfw58qiiss"; // database password
$dbname  = "tb4047rmgcwr9zh8"; // database name
if (empty($dbuser) || empty($dbname)) {
     die('connection to database error <br> please edit the includes/config.php <br> with the database details');
}
require_once('MysqliDb.php');
$db = new MysqliDb($dbhost, $dbuser, $dbpass, $dbname);
