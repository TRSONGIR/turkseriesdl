<?php
#########################
# upgrade from 2 to 3
# 12/4/2015
# this upgrade script from 1.4:6  to 1.7
# migrate to usercake
# 
########################

  function generateActivationToken($gen = null)
{
    $gen = md5(uniqid(mt_rand(), false));
    return $gen;
} 

$contents[] = "========================";
$contents[] = "upgrade script " . SCRIPT_SCHEMA_VERSION . " at " . date('r');
$contents[] = 'table users begins';
$db->query('ALTER TABLE admin RENAME TO users');
$table_user = 'users';
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$drop_columns_user = $db->query('ALTER TABLE users DROP COLUMN ads, DROP COLUMN config, DROP COLUMN admins, DROP COLUMN links');
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
// add columns
$add_columns = "ALTER TABLE users ADD
                         (
							is_admin tinyint(1) DEFAULT 0,
							activation_token varchar(225),
							last_activation_request	int(11),
							lost_password_request tinyint(1),
							active tinyint(1),
							title varchar(150),
							sign_up_stamp int(11),
							last_sign_in_stamp int(11)
                         )";
$add_columns_user = $db->query($add_columns);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
//create missing values for admin table
$table_user_table = $db->get($table_user); // 1 is number Of Rows optiona
   foreach($table_user_table as $value)
    {
     $data_array = Array (
    'active' => 1,
    'title' => $value['name'],  
    'is_admin' => 1,
    'activation_token' => md5(uniqid(mt_rand(), false))
        );
    $db->where ('id', $value['id']);
    $db->update ($table_user, $data_array); 

      }
    if (strlen($db->getLastError()) > 2 ) {
        $errors[] = $db->getLastError();
    } 
$contents[] = 'table users successfully updated';
$contents[] =  'done table users';
#====================
#table users ends
#=====================

// update schema version with the current
$contents[] = 'update schema version with the current version';
$data = Array ('schema_version' => SCRIPT_SCHEMA_VERSION);
$db->update ('config', $data) ;
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'done';
#====================
#table config begins
#====================
$contents[] = 'update table config begins';
$table_config = 'config';               
 $add_columns_config = "ALTER TABLE $table_config ADD
                         (
                            `resend_activation_threshold` varchar(150) DEFAULT NULL,
                            `activation` varchar(150) DEFAULT NULL,
                            `language` varchar(250) DEFAULT NULL
                         )";
 $add_columns_config = $db->query($add_columns_config);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
 // insert data to the config table
      $data_array = Array (
    'resend_activation_threshold' => 0,
    'activation' => 'false',  
    'language' => 'models/languages/en.php'
    
        );
 $db->update ($table_config, $data_array);   
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'table config done';
#====================
#table config ends
#====================

#====================
#table pages begins
#===================
$contents[] = 'upgrade table pages begins';

$create_table_pages = 'CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=98 ;';

 $create_table_pages_go = $db->query($create_table_pages);
 if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
 // insert data to the config table
 
$pages_insert = "INSERT INTO `pages` (`id`, `page`, `private`) VALUES
(70, 'addadmin.php', 1),
(71, 'addlink.php', 1),
(73, 'config.php', 1),
(74, 'display.php', 1),
(75, 'global.php', 1),
(76, 'index.php', 1),
(77, 'login.php', 1),
(78, 'login_falid.php', 1),
(79, 'logout.php', 0),
(80, 'multilink.php', 1),
(81, 'profile.php', 1),
(82, 'search.php', 1),
(83, 'showads.php', 1),
(84, 'smarty.php', 1),
(85, 'social.php', 1),
(86, 'viewadmins.php', 1),
(87, 'viewads.php', 1),
(88, 'viewlinks.php', 1);"  ;
$insert_table_pages_go = $db->rawQuery($pages_insert);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'table pages done';
#====================
#table pages ends
#===================

#====================
#table permissions begins
#===================
$contents[] = 'upgrade table permissions';
$create_table_permissions = 'CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;';
 $create_table_pages_go = $db->query($create_table_permissions);
 if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
 // insert data to the config table
 
$permissions_insert = "INSERT INTO `permissions` (`id`, `name`) VALUES
(1, 'home page'),
(2, 'manage admins'),
(3, 'manage ads'),
(4, 'manage socials'),
(5, 'manage links'),
(6, 'site settings');"  ;
$insert_table_pages_go = $db->rawQuery($permissions_insert);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'upgrade table permissions done';
#====================
#table permissions ends
#===================


#====================
# table permission_page_matches begins
#===================
$contents[] = 'upgrade table permission_page_matches begins';
$create_table_permission_page_matches = 'CREATE TABLE IF NOT EXISTS `permission_page_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;';
 $create_table_permission_page_matches_go = $db->query($create_table_permission_page_matches);
 if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
 // insert data to the config table
 
$permission_page_matches_insert = "INSERT INTO `permission_page_matches` (`id`, `permission_id`, `page_id`) VALUES
(34, 3, 83),
(35, 3, 87),
(36, 4, 85),
(37, 5, 71),
(38, 5, 80),
(39, 5, 82),
(40, 5, 88),
(50, 2, 70),
(56, 2, 86),
(58, 6, 73),
(59, 1, 76),
(60, 1, 81);"  ;
$insert_table_permission_page_matches_go = $db->rawQuery($permission_page_matches_insert);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'table permission_page_matches done';
#====================
# table permission_page_matches ends
#===================

#===================
# table user_permission_matches begins
#=========================
$contents[] = 'table user_permission_matches begins';
$create_table_user_permission_matches = 'CREATE TABLE IF NOT EXISTS `user_permission_matches` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `user_id` int(11) NOT NULL,
                        `permission_id` int(11) NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=131 ;';
 $create_table_user_permission_matches_go = $db->query($create_table_user_permission_matches);
 if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
 // insert data to the config table
 
$user_permission_matches_insert = "INSERT INTO `user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
                                    (19, 1, 3),
                                    (20, 1, 4),
                                    (21, 1, 5),
                                    (22, 1, 1),
                                    (23, 1, 2),
                                    (117, 1, 6);"  ;
$insert_table_user_permission_matches_go = $db->rawQuery($user_permission_matches_insert);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'table user_permission_matches done';

#===================
# table user_permission_matches ends
#=========================

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