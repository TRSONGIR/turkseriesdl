<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
// check refere to protect admin from CSRF attacks
check_ref ($domain);
if(!empty($_POST))
{
    $username       = $_POST['name'];
    $displayname   = $_POST['realname'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    //clean entries
    $username       = SanitizeString($db->escape($username));
    $displayname   = SanitizeString($db->escape($displayname));
    $email      = SanitizeString($db->escape($email));
    $password   = SanitizeString($db->escape($password));
    	if(minMaxRange(5,25,$username))
	{
		$errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($username)){
		$errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
	}
	if(minMaxRange(5,25,$displayname))
	{
		$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($displayname)){
		$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
	}
	if(minMaxRange(8,50,$password) )
	{
		$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
	}

	if(!isValidEmail($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
    if ($is_demo !== "1") {
	//End data validation
	if(count($errors) == 0)
	{	
		//Construct a user object
                $is_admin = 1;
		$user = new User($username,$displayname,$password,$email, $is_admin);
		
		//Checking this flag tells us whether there were any errors such as possible data duplication occured
		if(!$user->status)
		{
			if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
			if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
			if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));		
		}
		else
		{
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if(!$user->userCakeAddUser())
			{
				if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
				if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
			}
		}
	}
        if(count($errors) > 0) {
        $smarty->assign ("done", 2); // 2 for errors
        print_r($errors);
        $smarty->assign ("errors", $errors); // errors array
	}  
	if(count($errors) == 0) {
		$successes[] = $user->success;
                $smarty->assign ("done", 1);
	}  
    }


    
} else {

    $smarty->assign ("done", 0);
}
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'addadmin';
require 'display.php';
