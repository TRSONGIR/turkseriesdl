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
    <div id="wrapper">
<?php
  function generateActivationToken($gen = null)
{
    $gen = md5(uniqid(mt_rand(), false));
    return $gen;
} 
$rand_md5 = generateActivationToken ();
$time_now = time();
#########################
# created by Abdul Ibrahim
########################
// Construct default site path for inserting into the database
$hostname = $_SERVER['HTTP_HOST'];
$app_path = $_SERVER['PHP_SELF'];
// get server protocol
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
// Get the parent directory of this (the install) directory
$app_dir_raw = dirname(dirname($app_path));

// Replace backslashes in local root (if we're in a windows environment)
$app_dir = str_replace('\\', '/', $app_dir_raw);	

$url = $protocol . '://' . $hostname . $app_dir;

include '../includes/config.php';
include '../version.php';

$query = "show tables";
$tables = $db->rawQuery($query);
$tables_count =  count($tables); // number of tables if tables are exist
if (($tables_count > 0 ) ) {
    die('
    <div> 
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Database Error</h3>
                            </div>
                            <div class="panel-body">
                                <p>the datbase is not empty</p>
                                <p>please choose an empty database</p>
                                <p>and try again later</p>
                                <p>if you want to upgrade please click that button</p>
                                <a href="upgrade.php" >
                                <button type="button" class="btn btn-success center-block">Upgrade</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>');
} 
extract($_POST, EXTR_PREFIX_SAME, "wddx"); // make form fileds names as variable names
if (!empty($username) && !empty($password) && !empty($email) && !empty($siteurl)) { 
    // $_POST extracted data
    $username ; 
    $password = sha1($password);
    $email ;
    $siteurl ;
    // end $_POST extracted data
    $today = date("Y-m-d"); // format 2014-12-08
    // creating admin table
    $query = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL DEFAULT '',
            `username` varchar(255) NOT NULL DEFAULT '',
            `password` varchar(255) NOT NULL DEFAULT '',
            `email` varchar(255) NOT NULL DEFAULT '',
            `notes` varchar(2000) NOT NULL,
            `is_admin` tinyint(1) DEFAULT '0',
            `activation_token` varchar(225) DEFAULT NULL,
            `last_activation_request` int(11) DEFAULT NULL,
            `lost_password_request` tinyint(1) DEFAULT NULL,
            `active` tinyint(1) DEFAULT NULL,
            `title` varchar(150) DEFAULT NULL,
            `sign_up_stamp` int(11) DEFAULT NULL,
            `last_sign_in_stamp` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
        ";
    $db->rawQuery($query);
    // insert records to admin table
    $query = "INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `notes`,
        
            `is_admin`,
            `activation_token`,
            `last_activation_request`,
            `lost_password_request`,
            `active`,
            `title`,
            `sign_up_stamp`,
            `last_sign_in_stamp`
        ) 
        VALUES
        (1, 'super admin', '$username', '$password', '$email', 'Write something here to remember',
        1,
        '$rand_md5',
        0,
        0,
        1,
        'super admin',
        '$time_now',
        0
        );";
    $db->rawQuery($query);
    // creating ads table
    $query = "CREATE TABLE IF NOT EXISTS `ads` (
        `nowdate` date NOT NULL DEFAULT '0000-00-00',
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL DEFAULT '',
        `code` varchar(5000) NOT NULL,
        `clicks` int(11) NOT NULL DEFAULT '0',
        `active` tinyint(1) NOT NULL,
        `type` varchar(25) NOT NULL DEFAULT '',
        `admin_id` int(11) NOT NULL DEFAULT '0',
        `template` varchar(50) NOT NULL,
        PRIMARY KEY (`id`)
    )   ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
    $db->rawQuery($query);
    // insert records to ads table
    $query = "INSERT INTO `ads` (`nowdate`, `id`, `name`, `code`, `clicks`, `active`, `type`, `admin_id`, `template`)
     VALUES
    ('$today', 1, 'rectangle', '', 0, '1', 'squar', 1, 'default'),
    ('$today', 2, 'Leaderboard', '', 0, '1', 'rectangle', 1, 'default');";
    $db->rawQuery($query);
    // creating table config
    $query = "CREATE TABLE IF NOT EXISTS `config` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sitename` varchar(255) NOT NULL DEFAULT '',
            `siteurl` varchar(255) NOT NULL DEFAULT '',
            `sitetitle` varchar(255) NOT NULL DEFAULT '',
            `time` tinyint(100) NOT NULL DEFAULT '0',
            `email` varchar(255) NOT NULL DEFAULT '',
            `schema_version` varchar(10) DEFAULT NULL,
            `template` varchar(100) NOT NULL DEFAULT '',
            `keywords` varchar(255) NOT NULL,
            `description` varchar(500) NOT NULL,
            `vistis` int(255) NOT NULL DEFAULT '0',
            `sitestate` tinyint(1) NOT NULL DEFAULT '0',
            `sitestatemsg` varchar(255) NOT NULL,
            `analytics` varchar(1000) DEFAULT NULL,
            `resend_activation_threshold` varchar(150) DEFAULT NULL,
            `activation` varchar(150) DEFAULT NULL,
            `language` varchar(50) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
    $db->rawQuery($query);
    // insert records to table config
    $query = "INSERT INTO `config` (`id`, `sitename`, `siteurl`, `sitetitle`, `time`, `email`, `schema_version`, `template`, `keywords`, `description`, `vistis`, `sitestate`, `sitestatemsg`, `analytics`,
                `resend_activation_threshold`, `activation`, `language`
                ) 
        VALUES
    (1, 'shorten and protect links', '$siteurl', 'short &amp; protect', 0, '$email',
        '$SCRIPT_SCHEMA_VERSION', 'default', 'short, shorten, tiny url, url shortner, url shrink',
        'Make a long URL short, easy to remember and to share it with friends and others', 0, 1,
    'sorry the website is closed for maintenance ', '<script>\r\n</script>',
    '0',
    'false',
    'models/languages/en.php'
        );";
    $db->rawQuery($query);
    
    // creating table links
    $query = "CREATE TABLE IF NOT EXISTS `links` (
        `id` int(255) NOT NULL AUTO_INCREMENT,
        `url` varchar(255) NOT NULL DEFAULT '',
        `name` varchar(255) NOT NULL DEFAULT '',
        `size` int(255) NOT NULL DEFAULT '0',
        `author` varchar(255) NOT NULL DEFAULT '',
        `admin_id` int(255) NOT NULL DEFAULT '0',
        `downloads` int(255) NOT NULL DEFAULT '0',
        `date` date NOT NULL DEFAULT '0000-00-00',
        PRIMARY KEY (`id`)
    )   ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
    $db->rawQuery($query);
    // creating table online
    $query = "CREATE TABLE IF NOT EXISTS `online` (
        `ip` varchar(100) NOT NULL DEFAULT '',
        `time` varchar(15) NOT NULL DEFAULT ''
    )   ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    $db->rawQuery($query);
    // creating table social
    $query = "CREATE TABLE IF NOT EXISTS `social` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT '',
        `code` varchar(255) NOT NULL,
        `active` tinyint(1) NOT NULL,
        PRIMARY KEY (`id`)
    )   ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
    $db->rawQuery($query);
    // insert records to table social
    $query = "INSERT INTO `social` (`id`, `name`, `code`, `active`) VALUES
        (1, 'facebook', 'https://www.facebook.com/', '1'),
        (2, 'twitter', 'https://twitter.com/', '1'),
        (3, 'google-plus', 'https://plus.google.com', '1'),
        (4, 'dribbble', 'https://dribbble.com', '1'),
        (5, 'linkedin', 'https://www.linkedin.com', '1');";
    $db->rawQuery($query);
    // end schema 1
    
    // start schema 2
    $sql_option = 'CREATE TABLE IF NOT EXISTS `options` (
    `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `option_name` varchar(100) NOT NULL,
    `option_value` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`option_id`),
    UNIQUE KEY `option_name` (`option_name`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';
    $db->rawQuery($sql_option);
    $link_window = Array ("option_name" => "link_window",
               "option_value" => "1",
    );
    $id = $db->insert('options', $link_window);
    $second_page = Array ("option_name" => "second_page",
               "option_value" => "1",
    );
    $id = $db->insert('options', $second_page);
    // end schema 2
    
     // start schema 3
    #====================
#table pages begins
#===================
$contents[] = 'upgrade table pages begins';

$create_table_pages = 'CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=98 ;';

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;';
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
    
    
     // end schema 3
    // end schema 3
    
     // start schema 5
        $contents[] = "========================";
       $contents[] = "upgrade script " . SCRIPT_SCHEMA_VERSION . " at " . date('r');
       $contents[] = 'convert options=>option_value to varchar(150) instead of tinint(1)  begins';
       $sql_CONVERT =            "ALTER TABLE `options` MODIFY `option_value` varchar(150)";
       $db->rawQuery($sql_CONVERT);

       if (strlen($db->getLastError()) > 2 ) {
           $errors[] = $db->getLastError();
       }
       #######################
       $contents[] = '======================';
       $contents[] = 'Add 3 values to the option table`';
       $sql_INSERT = "INSERT INTO options (option_name,option_value) VALUES
               ('recaptcha_status', '1'),
               ('recaptcha_Site_key','enter your recaptcha Site key read documentation'),
               ('recaptcha_Secret_key','enter your recaptcha_Secret_key read documentation')";
       $db->rawQuery($sql_INSERT);

       if (strlen($db->getLastError()) > 2 ) {
           $errors[] = $db->getLastError();
       }
       // update schema version with the current
       $contents[] = 'update schema version with the current version';
       $data = Array ('schema_version' => SCRIPT_SCHEMA_VERSION);
       $db->update ('config', $data) ;
       if (strlen($db->getLastError()) > 2 ) {
           $errors[] = $db->getLastError();
       }
     // end schema 5
    #====================
    echo '
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">    
                                    <div class="panel panel-success">
                                            <div class="panel-heading">installation succeeded
                                            </div>
                                            <div class="panel-body">
                                                    <p>the Installation was successful</p>
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
                }, 2000)  
            </script>
    
';
    
    
} else {
    
?>
        
<div class="col-md-4 col-md-offset-5">   
    <h3>Please fill in all the fields</h3>
    <form role="form" method="post">
        <div class="form-group">
          <label for="Name">Username</label>
          <input type="text" class="form-control" name="username" placeholder="Enter username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" name="password" id="Password1" placeholder="Password" required>
        </div>
        <div class="form-group">
          <label for="email">email</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="email" required>
        </div>
        <div class="form-group">
            <label for="siteurl">site URL <small>without a backslash "/"</small></label>
          <input type="url" class="form-control" name="siteurl" id="siteurl" value="<?php echo $url ?>" required>
        </div>  
        <button type="submit" class="btn btn-success">Submit</button>
    </form>             
</div>
<?php
}
?>
