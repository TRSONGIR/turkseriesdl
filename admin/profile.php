<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
// check refere to protect admin from CSRF attacks
check_ref ($domain);
$admin_id     =  $_SESSION["userCakeUser"]->user_id;
if (isset($_POST['form_action']) 
    && $_POST['form_action'] == 1  
    && isset($_POST['name']) 
    && !empty($_POST['email']) 
    && !empty($_POST['username'])) { 
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = trim(sha1($_POST['password']));
    $data_array['name']     = SanitizeString($db->escape($name));
    $data_array['username'] = SanitizeString($db->escape($username));    
    $data_array['email'] = SanitizeString($db->escape($email));
    if ((strlen($_POST['password']) > 1)) {
        $password  = sha1($_POST['password']);
        $data_array['password'] = SanitizeString($db->escape($password));
    }    
    
     if ($is_demo !== "1") {  
    $db->where ('id', $admin_id);  // column name and value
    if(!$db->update ('users', $data_array)) echo 'Error happened'; 
     }

    $smarty->assign ("done", 1);
} else {
    $smarty->assign ("done", 0);
    $query  ="SELECT * FROM `users` where `id` = '$admin_id' ";
    $db->where ('id', $admin_id);  // column name and value
    $row = $db->get ('users'); // table name
    $fetch  = $row[0];
    $smarty->assign ("name",     $fetch['name']);
    $smarty->assign ("username", $fetch['username']);
    $smarty->assign ("email",    $fetch['email']);
    $smarty->assign ("password", $fetch['password']);
}
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'profile';
require 'display.php';
