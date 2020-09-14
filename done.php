<?php
require 'includes/config.php';
require 'smarty.php';

if($_POST['form_action'] == 1 && isset($_POST['url']) && !empty($_POST['url'])){
    if ($recaptcha_status == 1 ) {
if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        //your site secret key
        $secret = $recaptcha_Secret_key;
        //get verify response data
		require('classes/recaptcha/src/autoload.php');		
		
		$recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
         if ($resp->isSuccess()) {

        if (empty($_POST['author'])) {
            $author = 'unknown';
        } else {
           $author = $_POST['author']; 
        }
        
         if (empty($_POST['name'])) {
            $name = 'unknown';
        } else {
           $name   = $_POST['name'];
        }
        $url    = $_POST['url'];
        $url    = trim($url);
        if (empty($_POST['size'])) {
            $size = 0;
        } else {
           $size   = $_POST['size'];
        }
        $date   = date("Y-m-d");
        $table  = 'links';  
        // //Sanitize the query array to insert into database
        $data_array['author']   = SanitizeString($db->escape($author));
        $data_array['name']     = SanitizeString($db->escape($name));
        $data_array['url']      = SanitizeString($db->escape($url));
        $data_array['size']     = SanitizeString($db->escape($size));
        $data_array['date']     = SanitizeString($db->escape($date));
        if (!$id = $db->insert($table, $data_array)) {
            echo '<script>alert("we got a database problem please try again latter");</script>';

        } 	
        $rand   =  $id;
        $smarty->assign ("rand", $rand);
        } else {
                $err_msg = 'Robot verification failed, please try again.';
                $smarty->assign ("err_msg", $err_msg);
            }
        } else {
            $err_msg = 'Please click on the reCAPTCHA box.';
            $smarty->assign ("err_msg", $err_msg);
        }
    } else {
        if (empty($_POST['author'])) {
            $author = 'unknown';
        } else {
           $author = $_POST['author']; 
        }
        
         if (empty($_POST['name'])) {
            $name = 'unknown';
        } else {
           $name   = $_POST['name'];
        }
        $url    = $_POST['url'];
        $url    = trim($url);
        if (empty($_POST['size'])) {
            $size = 0;
        } else {
           $size   = $_POST['size'];
        }
    $date   = date("Y-m-d");
    $table  = 'links';  
    // //Sanitize the query array to insert into database
    $data_array['author']   = SanitizeString($db->escape($author));
    $data_array['name']     = SanitizeString($db->escape($name));
    $data_array['url']      = SanitizeString($db->escape($url));
    $data_array['size']     = SanitizeString($db->escape($size));
    $data_array['date']     = SanitizeString($db->escape($date));
    if (!$id = $db->insert($table, $data_array)) {
        echo '<script>alert("we got a problem please try again latter");</script>';
        
    } 
    $rand   =  $id;
    $smarty->assign ("rand", $rand);  
    }

	
} else {
    header("Location: index.php");
    die();
}
$templatefile = 'done';
require 'display.php';
