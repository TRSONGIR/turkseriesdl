<?php
// started at 8/11/2014
require 'includes/config.php';
require 'smarty.php';
$time     = time();  // time now
$ip       = $_SERVER['REMOTE_ADDR'];  // vistor ip
$limitsec = $time-300; // 5 minutes to calculate who's online
// update online table
$query = ("DELETE from  online where ip='$ip' OR  time<'$limitsec'"); 
$db->rawQuery($query);
// end online table
// insert to online table
$query="INSERT INTO online (ip,time) VALUES ('$ip','$limitsec')";
$db->rawQuery($query);
// end insert online 
// update visits table
$table = 'config';                              //this table name
$data_array['vistis'] = $db->inc(1);
$db->update($table, $data_array);
// end update
$templatefile = 'index';
require 'display.php';
