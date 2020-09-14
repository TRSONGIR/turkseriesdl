<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
///////////

$adminid = $_SESSION["userCakeUser"]->user_id;

// check refere to protect admin from CSRF attacks
check_ref ($domain);
if (isset($_POST['form_action']) 
    && $_POST['form_action'] == 1 
    && !empty($_POST['multiurls']) 
    && substr($_POST['multiurls'], 0, 4) == 'http') {
    $date   = date('Y-m-d');
    $url    = addslashes($_POST['multiurls']);
    $url    = nl2br($url);
    $sites  = explode("<br />",$url);
    foreach ($sites as $value ) {
        $value = trim($value);
        $data_array['url']      = SanitizeString($db->escape($value));
        $data_array['date']     = SanitizeString($db->escape($date));
        $data_array['admin_id'] = SanitizeString($db->escape($adminid));
        if ($is_demo !== "1") {
            $id = $db->insert('links', $data_array);
            $links[] = $id ;
            $smarty->assign ("urls", $links);
            $smarty->assign ("done", 1);
        }
    }
} else {
    $smarty->assign ("done", 0);
}
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'multilink';
require 'display.php';
