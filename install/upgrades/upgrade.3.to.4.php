<?php

##################################################
# Created by Abdul Ibrahim
# Jul 1, 2015 5:47:04 PM
# website http://www.abdulibrahim.com/
# migrate to version 4
##################################################
$contents[] = "========================";
$contents[] = "upgrade script " . SCRIPT_SCHEMA_VERSION . " at " . date('r');
$contents[] = 'table change tables  CHARACTER SET begins';

$sql_CONVERT[] =            "ALTER TABLE users CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =            "ALTER TABLE social CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =            "ALTER TABLE pages CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =           " ALTER TABLE online CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =           " ALTER TABLE links CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =            "ALTER TABLE config CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =            "ALTER TABLE ads CONVERT TO CHARACTER SET utf8;";
$sql_CONVERT[] =            "ALTER TABLE permission_page_matches CONVERT TO CHARACTER SET utf8;";

foreach ($sql_CONVERT as $query) {
   $db->rawQuery($query);
    if (strlen($db->getLastError()) > 2 ) {
        $errors[] = $db->getLastError();
    } 
}            
$contents[] = '============================';
$contents[] = 'ALTER TABLE ads';
$sql_ads = "
            ALTER TABLE `ads` 
            MODIFY  `id` int(11) NOT NULL AUTO_INCREMENT,
            MODIFY  `name` varchar(100) NOT NULL DEFAULT '',
            MODIFY  `code` varchar(5000) NOT NULL,
            MODIFY  `active` tinyint(1) NOT NULL,
            MODIFY  `type` varchar(25) NOT NULL DEFAULT '',
            MODIFY  `admin_id` int(11) NOT NULL DEFAULT '0',
            MODIFY  `template` varchar(50) NOT NULL
         ";

$db->rawQuery($sql_ads);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}

$contents[] = '===============================';
$contents[] = 'ALTER TABLE config' ;
$sql_config = "
                ALTER TABLE `config`
                MODIFY  `id` int(11) NOT NULL AUTO_INCREMENT,
                MODIFY  `time` tinyint(100) NOT NULL DEFAULT '0',
                MODIFY  `email` varchar(255) NOT NULL DEFAULT '',
                MODIFY  `template` varchar(100) NOT NULL DEFAULT '',
                MODIFY  `keywords` varchar(255) NOT NULL,
                MODIFY  `description` varchar(500) NOT NULL,
                MODIFY  `sitestate` tinyint(1) NOT NULL DEFAULT '0',
                MODIFY  `sitestatemsg` varchar(255) NOT NULL,
                MODIFY  `analytics` varchar(1000) DEFAULT NULL,
                MODIFY  `language` varchar(50) DEFAULT NULL
               "; 

$db->rawQuery($sql_config);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = '========================================';
$contents[] = 'ALTER TABLE links';

$sql_links = "
            ALTER TABLE `links` 
            MODIFY  `id` int(255) NOT NULL AUTO_INCREMENT
              ";  

$db->rawQuery($sql_links);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}  
$contents[] = '======================';
$contents[] = 'ALTER TABLE `options`';

$sql_options = "
                ALTER TABLE `options`
                MODIFY  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                MODIFY  `option_value` tinyint(1) unsigned NOT NULL
                ";

$db->rawQuery($sql_options);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}  
$contents[] = '=======================';
$contents[] = 'ALTER TABLE social';
$sql_social = "
                ALTER TABLE `social` 
                MODIFY  `id` int(11) NOT NULL AUTO_INCREMENT,
                MODIFY  `code` varchar(255) NOT NULL,
                MODIFY  `active` tinyint(1) NOT NULL
               "; 

$db->rawQuery($sql_social);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = '=======================';
$contents[] = 'ALTER TABLE users';

$sql_users = "
                ALTER TABLE `users` 
                MODIFY  `id` int(11) NOT NULL AUTO_INCREMENT,
                MODIFY  `notes` varchar(2000) NOT NULL
            ";
$db->rawQuery($sql_users );

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
// check if the query didn't run successfully

if ( sizeof($errors) > 0 ) {
    echo 'we got error in upgrading the script Please look into upgrade_log.txt file for details';
    $contents[] = 'error reported to the user';
} else {
    echo '            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">    
                                    <div class="panel panel-success">
                                        <div class="panel-heading">installation succeeded
                                        </div>
                                        <div class="panel-body">
                                            <p>upgrade the script to ' . SCRIPT_VERSION . ' version was successful</p>
                                            <p>You will be redirected now to the admin panel</p>
                                            <p>Please wait...</p>    
                                        </div>
                                    </div>  
                            </div> 
                    </div> 
            </div>
            <script>
                window.setTimeout(function () {
                location.href = "../admin";
                }, 5000)  
            </script>
    
';
    $contents[] = 'successful upgrade was successfully reported to the user';
 
}
