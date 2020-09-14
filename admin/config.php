<?php
require '../includes/config.php';
require 'smarty.php';
require 'global.php';
// check refere to protect admin from CSRF attacks
check_ref ($domain);
if(isset($_POST['form_action']) && $_POST['form_action'] == 1){
    $sitename           = $_POST['sitename'];
    $siteurl            = $_POST['siteurl'];
    $sitetitle          = $_POST['sitetitle'];
    $description        = $_POST['description'];
    $email              = $_POST['email'];
    $template           = $_POST['template'];
    $time               = $_POST['time'];
    $keywords           = $_POST['keywords'];
    $sitestatemsg       = $_POST['sitestatemsg'];
    $sitestate          = $_POST['sitestate'];
    $analytics          = $_POST['analytics'];
    // table options
    $link_window        = $_POST['link_window'];
    $second_page        = $_POST['second_page'];
    // recaptcha option
    $recaptcha_status   = $_POST['recaptcha_status'];
    $recaptcha_Site_key = $_POST['recaptcha_Site_key'];
    $recaptcha_Secret_key = $_POST['recaptcha_Secret_key'];
    
    $data_array['sitename'] = SanitizeString($db->escape($sitename));
    $data_array['siteurl'] = SanitizeString($db->escape($siteurl));
    $data_array['sitetitle'] = SanitizeString($db->escape($sitetitle));
    $data_array['description'] = SanitizeString($db->escape($description));
    $data_array['email'] = SanitizeString($db->escape($email));
    $data_array['template'] = SanitizeString($db->escape($template));
    $data_array['time'] = SanitizeString($db->escape($time));
    $data_array['keywords'] = SanitizeString($db->escape($keywords));
    $data_array['sitestate'] = SanitizeString($db->escape($sitestate));
    $data_array['sitestatemsg'] = SanitizeString($db->escape($sitestatemsg));
    $data_array['analytics'] = $analytics;  // not sanitized to allow javascript code
    
    
    if ($is_demo !== "1") {
        $db->where ('id', 1);
        $db->update ('config', $data_array);
    }
    // table options update link_window
    $data_link_window['option_value'] = SanitizeString($db->escape($link_window));
    
    if ($is_demo !== "1") {
        $db->where ('option_id', 1);
        $db->update ('options', $data_link_window);
    }
    // table options update second_page
    $data_second_page['option_value'] = SanitizeString($db->escape($second_page));
    if ($is_demo !== "1") {
        $db->where ('option_id', 2);
        $db->update ('options', $data_second_page);
    }
    //  recaptcha options
    $data_recaptcha_status['option_value'] = SanitizeString($db->escape($recaptcha_status));
    if ($is_demo !== "1") {
    $db->where ('option_id', 3);
    $db->update ('options', $data_recaptcha_status);
    }
    $data_recaptcha_Site_key['option_value'] = SanitizeString($db->escape($recaptcha_Site_key));
    if ($is_demo !== "1") {
    $db->where ('option_id', 4);
    $db->update ('options', $data_recaptcha_Site_key);
    } 
    $data_recaptcha_Secret_key['option_value'] = SanitizeString($db->escape($recaptcha_Secret_key));
    if ($is_demo !== "1") {
    $db->where ('option_id', 5);
    $db->update ('options', $data_recaptcha_Secret_key);
    }        
    $smarty->assign ("done", 1);
        
} else {
    $smarty->assign ("done", 0);
    $query  = "SELECT * FROM `config` where `id` = '1' ";
    $rows   = $db->rawQuery($query);
    $fetch  = $rows[0];
    // replace \r\n with new line
    $fetch['sitestatemsg'] = replace_slashes($fetch['sitestatemsg']);
    $fetch['description'] = replace_slashes($fetch['description']);
    $fetch['keywords'] = replace_slashes($fetch['keywords']);
    
    $smarty->assign ("sitename", $fetch['sitename']);
    $smarty->assign ("sitetitle", $fetch['sitetitle']);
    $smarty->assign ("siteurl", $fetch['siteurl']);
    $smarty->assign ("time", $fetch['time']);
    $smarty->assign ("template", $fetch['template']);
    $smarty->assign ("description", $fetch['description']);
    $smarty->assign ("keywords", $fetch['keywords']);
    $smarty->assign ("email", $fetch['email']);
    $smarty->assign ("sitestate", $fetch['sitestate']);
    $smarty->assign ("sitestatemsg", $fetch['sitestatemsg']);
    $smarty->assign ("analytics", $fetch['analytics']);
    $dir = "../templates";
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if($file != "." and $file != "index.php" and $file != "close.html" and $file != "copyright.html" and $file != $fetch['template'] and $file != "..") {
                    $files[] = $file;
                    $smarty->assign ("templates", $files);
                }
            }
        }
        closedir($dh);
    }
    // for options table
    $query_options          = "SELECT * FROM `options`";
    $rows_options           = $db->rawQuery($query_options);
    $link_window            = $rows_options[0]['option_value'];
    $second_page            = $rows_options[1]['option_value'];
    $recaptcha_status       = $rows_options[2]['option_value'];
    $recaptcha_Site_key     = $rows_options[3]['option_value'];
    $recaptcha_Secret_key   = $rows_options[4]['option_value'];  
    $smarty->assign ("link_window",    $link_window);
    $smarty->assign ("second_page",    $second_page);
    $smarty->assign ("recaptcha_status",   $recaptcha_status);
    if ($is_demo == 1) {
       $smarty->assign ("recaptcha_Site_key",       '***********'); 
    } else {
        $smarty->assign ("recaptcha_Site_key",      $recaptcha_Site_key); 
    }
    if ($is_demo == 1) {
       $smarty->assign ("recaptcha_Secret_key",     '***********'); 
    } else {
        $smarty->assign ("recaptcha_Secret_key",    $recaptcha_Secret_key);
    }
    
}
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'config';
require 'display.php';
