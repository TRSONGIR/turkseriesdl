<?php
require 'includes/config.php';
require 'smarty.php';
if (isset($_POST['form_action']) 
        && $_POST['form_action'] == 1 
        && !empty($_POST['multiurls']) 
        && substr($_POST['multiurls'], 0, 4) == 'http') {
    // recapcha
    if ($recaptcha_status == 1 ) {
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = $recaptcha_Secret_key;
        //get verify response data
		require('classes/recaptcha/src/autoload.php');		
		
		$recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
         if ($resp->isSuccess()) {
    $date   = date('Y-m-d');
    $url    = addslashes($_POST['multiurls']);
    $url    = nl2br($url);
    $sites  = explode("<br />",$url);
    foreach ($sites as $value ) {
        $value  = trim($value);
        $data_array ['url']= SanitizeString($db->escape($value));
        $data_array ['date']= $date;
        if (!$id = $db->insert('links', $data_array)) {
            echo 'we got a problem please try again latter';    
        }
        $links[] = $id;
        $smarty->assign ("links", $links);
        $smarty->assign ("done", 1);
    }
} else {
            $err_msg = 'Robot verification failed, please try again.';
            $smarty->assign ("err_msg", $err_msg);
        }
    } else {
        $err_msg = 'Please click on the reCAPTCHA box.';
        $smarty->assign ("err_msg", $err_msg);
    }
    } else { 
        $date   = date('Y-m-d');
        $url    = addslashes($_POST['multiurls']);
        $url    = nl2br($url);
        $sites  = explode("<br />",$url);
        foreach ($sites as $value ) {
            $value  = trim($value);
            $data_array ['url']= SanitizeString($db->escape($value));
            $data_array ['date']= $date;
            if (!$id = $db->insert('links', $data_array)) {
                echo 'we got a problem please try again latter';    
            }
            $links[] = $id;
            $smarty->assign ("links", $links);
            $smarty->assign ("done", 1);
        }
    }
} else {
    $smarty->assign ("done", 0);
}
$templatefile = 'multiadd';
require 'display.php';
