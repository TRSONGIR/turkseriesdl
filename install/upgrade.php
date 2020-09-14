<!DOCTYPE html>
<html lang="en">
<head>

    <title>Admin Panel</title>
    <!-- Bootstrap Core CSS -->
    <link href="../templates/default/css/bootstrap.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- Custom Fonts -->
    <script src="../templates/default/js/bootstrap.min.js"></script>
</head>
<body>
<?php
#########################
# update script version 1
# 16/12/2014
########################
function upgrade_log ($contents, $errors){
    foreach ($contents as $data) {
        file_put_contents(UPGRADE_LOG, $data, FILE_APPEND);
        file_put_contents(UPGRADE_LOG, "\n", FILE_APPEND);
    }
    foreach ($errors as $err) {
    if (strlen ($err) > 1){
        file_put_contents(UPGRADE_LOG, $err, FILE_APPEND);
        file_put_contents(UPGRADE_LOG, "\n", FILE_APPEND);
    }
    }
}
#########################
# $filename = cms_join_path('upgrades', "upgrade.{$current_version}.to." . ($current_version+1) . '.php');
# will be upgrades\upgrade.2.to.3.php
########################
function cms_join_path()
{
	$args = func_get_args(); // array of all the function arguments
        return implode(DIRECTORY_SEPARATOR,$args);
}
include '../includes/config.php';
include '../version.php';
define('UPGRADE_LOG', "upgrade_log.txt");
// cerate log contents array for and error array
$contents   = array();
$errors     =  array();
// get the current schema version to start update
$config = $db->getOne('config');
// if the column toplinks so it means we didn't ever modify the datbase ever
//  so  it's schema version is 1
if (isset($config['toplinks'])){
   $current_version = 1;
} else {
    // get the schema version from database
    $current_version = $config['schema_version'];
}
if ( ($current_version < SCRIPT_SCHEMA_VERSION) )
{
       $contents[] = 'need_upgrade_schema' . $current_version .'to' . SCRIPT_SCHEMA_VERSION ;
        while ($current_version < SCRIPT_SCHEMA_VERSION)
        {
                $filename = cms_join_path('upgrades', "upgrade.{$current_version}.to." . ($current_version+1) . '.php');
                if (file_exists($filename))
                {
                        include($filename);
                }
                else
                {
                        echo 'updrade file' . ": $filename dosn't exist";
                }
                $current_version++;
        }
        
} else {
       echo  'no need to upgrade schema your database is up to date it\'s version is ' . SCRIPT_SCHEMA_VERSION;
}

// add contents to log
upgrade_log ($contents, $errors);
$data = Array ('schema_version' => SCRIPT_SCHEMA_VERSION);
$db->update ('config', $data) ;