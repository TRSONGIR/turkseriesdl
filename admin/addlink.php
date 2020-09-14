<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
// check refere to protect admin from CSRF attacks
check_ref ($domain);
if(isset($_POST['form_action']) 
        && $_POST['form_action'] == 1 
        && isset($_POST['url']) 
        && !empty($_POST['url'])){
    $name     = $_POST['name'];
    $url      = $_POST['url'];
    $url      = trim($url);
    $size     = $_POST['size'];
    $author   = $_POST['author'];
    $date     = date("Y-m-d");
    $adminid  = $_SESSION["userCakeUser"]->user_id;
    $data_array['name'] = SanitizeString($db->escape($name));
    $data_array['url'] = SanitizeString($db->escape($url));
    $data_array['size'] = SanitizeString($db->escape($size));
    $data_array['author'] = SanitizeString($db->escape($author));
    $data_array['date'] = SanitizeString($db->escape($date));
    $data_array['admin_id'] = SanitizeString($db->escape($adminid));
    if ($is_demo !== "1") {
        $id = $db->insert('links', $data_array);   
        $rand = $id;
        $smarty->assign ("rand", $rand); 
        $smarty->assign ("done", 1);
    }
} else {
    $smarty->assign ("done", 0);
}
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'addlink';
require 'display.php';
