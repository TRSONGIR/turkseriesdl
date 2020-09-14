<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
// check refere to protect admin from CSRF attacks
check_ref ($domain);
if (empty($_GET['do'])) {
    $_GET['do'] = "ffffff";    
}
if (empty($_GET['page'])) {
    $_GET['page'] = "1";    
}
$userId = $_SESSION["userCakeUser"]->user_id;

//List of permission levels user is apart of

$do = $_GET['do'];
switch($do){
    case "del":
    if(!empty($_POST['check'])){
        foreach($_POST['check'] as $v){
            if ($v == 1) {
                header("location: viewadmins.php");
                die();
                }else{
                $del = "DELETE FROM users WHERE id=$v";
                $del_permissions = "DELETE FROM user_permission_matches WHERE user_id = $v";
                if ($is_demo !== "1") {
                    $del = SanitizeString($db->escape($del)); 
                    $result = $db->rawQuery($del);
                    $del_permissions = SanitizeString($db->escape($del_permissions)); 
                    $result = $db->rawQuery($del_permissions);
                }
                
                $smarty->assign ("action", "del");
            }
        }
    }
	break;
    case "update":
	$smarty->assign ("action", "update");
	$id = $_GET['id'];
        $userPermission = fetchUserPermissions($id);
        $permissionData = fetchAllPermissions();
        // remove the first element which contain the admin home page
        unset($permissionData[0]);
        foreach ($permissionData as $v1) {
            if(isset($userPermission[$v1['id']])){
                $remove[] = $v1;
                $smarty->assign ("remove", $remove);
            }
        }
        //List of permission levels user is not apart of
        foreach ($permissionData as $v1) {
            if(!isset($userPermission[$v1['id']])){
                $add [] = $v1;
                $smarty->assign ("add", $add);
            }
        }
	if(!empty($_POST['form_action']) && $_POST['form_action'] == 1 && $_GET['id'] != 1  && !empty($_POST['name']) ){
            $name      = $_POST['name'];

            $realname  = $_POST['realname'];
            $email     = $_POST['email'];
            $name      = SanitizeString($db->escape($name )); 
            $realname  = SanitizeString($db->escape($realname )); 
            $email     = SanitizeString($db->escape($email )); 
//            $links     = SanitizeString($db->escape($links)); 
//            $config    = SanitizeString($db->escape($config)); 
//            $ads       = SanitizeString($db->escape($ads)); 
//            $admin     = SanitizeString($db->escape($admin));
            // check if the password field is not empty

            if ((strlen($_POST['password']) > 1)) {
                $password  = sha1($_POST['password']);
                $password  = SanitizeString($db->escape($password));
                $query     = "UPDATE `users` SET `name` = '$realname', `password` = '$password', `username` = '$name', `email` = '$email' WHERE `id` = '$id'";
            } else {
                $query     = "UPDATE `users` SET `name` = '$realname', `username` = '$name', `email` = '$email'  WHERE `id` = '$id'";
            }
            if ($is_demo !== "1") {
                $row    = $db->rawQuery($query);
            }
            // update permissions
                if(!empty($_POST['removePermission'])){
                $remove = $_POST['removePermission'];
                if ($deletion_count = removePermission($remove, $id)){
                        $successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array ($deletion_count));
                }
                else {
                        $errors[] = lang("SQL_ERROR");
                }
		}
		if(!empty($_POST['addPermission'])){
			$add = $_POST['addPermission'];
			if ($addition_count = addPermission($add, $id)){
				$successes[] = lang("ACCOUNT_PERMISSION_ADDED", array ($addition_count));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
            $smarty->assign ("done", 1);
            $smarty->assign ("id",          $id);
        } else {
            $smarty->assign ("done", 0);
            $query  = "SELECT * FROM `users` where `id` = '$id' ";
            $row    = $db->rawQuery($query);
            $fetch  = $row[0];
            $smarty->assign ("name",        $fetch['username']);
            $smarty->assign ("realname",    $fetch['name']);
            $smarty->assign ("email",       $fetch['email']);
            $smarty->assign ("password",    $fetch['password']);
            $smarty->assign ("id",          $id);
        }
        break;
    case "show":
        $id = $_GET['id'];

        $smarty->assign ("admin_id_id", "$id");
        $smarty->assign ("action", "show");
        $query  = "SELECT * FROM `users` where `id` =$id ";
        $row    = $db->rawQuery($query);
        $fetch  = $row[0];
        $smarty->assign ("admin_name", $fetch['name']);
        $query  = "SELECT COUNT(*) as num FROM `links` where `id` =$id";
        $row    = $db->rawQuery($query);
        $admin_num_links = $row[0]['num'];
        $smarty->assign ("admin_num_links", $admin_num_links);
        $tbl_name = "users";//your table name
        // How many adjacent pages should be shown on each side?
        $adjacents = 3;
        /*
        First get total number of rows in data table.
        If you have a WHERE clause in your query, make sure you mirror it here.
        */
        $query = "SELECT COUNT(*) as num FROM $tbl_name where id = $id";
        $total_pages = $db->rawQuery($query);
        $total_pages = $total_pages[0]['num'];
        /* Setup vars for query. */
        $targetpage = "viewadmins.php?do=show&id=$id";     //your file name  (the name of this file)
        $limit = 10; //how many items to show per page
        $page = $_GET['page'];
        if($page)
        $start = ($page - 1) * $limit;     //first item to display on this page
        else
        $start = 0;//if no page var is given, set start to 0
        /* Get data. */
        $sql = "SELECT * FROM $tbl_name where id = $id order by `date` desc  LIMIT $start, $limit";
        $result = $db->rawQuery($sql);
        /* Setup page vars for display. */
        if ($page == 0) $page = 1;    //if no page var is given, default to 1.
        $prev = $page - 1;    //previous page is page - 1
        $next = $page + 1;    //next page is page + 1
        $lastpage = ceil($total_pages/$limit);//lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;//last page minus 1
        /*
        Now we apply our rules and draw the pagination object.
        We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $pagination = array();
        if ($lastpage > 1) {
        //previous button
            if ($page > 1) {
                $pagination[] = '<li><a href="'.$targetpage.'?page='.$prev.'">&laquo; previous</a></li>';
            } else {
                $pagination[] = '<li class="disabled"><a href="#"> &laquo; previous</a></li>';
            }
            $pagination[]="&nbsp;&nbsp;";
            //pages
            if ($lastpage < 7 + ($adjacents * 2)) {//not enough pages to bother breaking it up

                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination[] = "<li class=\"active\"><a href=\"#\">$counter</a></li>";    
                    } else {
                        $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";   
                    }
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                            if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                            } else {
                            $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";
                            }
                        }
                        $pagination[] = "<li><a href=\"$targetpage?page=$lpm1\">$lpm1</a></li>";
                        $pagination[] = "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination[] = "<li><a href=\"$targetpage?page=1\">1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=2\">2</a><li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"$counter\">$counter</a></li>";  
                        } else {
                            $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";  
                        }
                    }
                    $pagination[] = "<li><a href=\"$targetpage?page=$lpm1\">$lpm1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";
                } else { //close to end; only hide early pages
                    $pagination[] = "<li><a href=\"$targetpage?page=1\">1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=2\">2</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"$counter\">$counter</a></li>";  
                        } else {
                            $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";   
                        }
                    }
                }
            }
            //next button
            if ($page < $lastpage ) {
                $pagination[] = "<li><a href=\"$targetpage?page=$next\">Next &raquo;  </a></li>";
            } else {
                $pagination[] = '<li class="disabled"><a href="#">Next &raquo; </a></li>';
            }
            $output  = '<ul class="pagination">';;
            foreach ($pagination as $pagina) {
                $output .= $pagina;
            }
            $output .= "</ul>";
        }
        $urls =	 array();
        foreach($result as $row) {
            $admin_id = $row['admin_id'];
            $admin = "SELECT * FROM users where id=$admin_id";
            $admin_sql = $db->query($admin);
            if (sizeof($admin_sql) > 0) {
               $admin_name = $admin_sql[0]['name'];
            } else {
                $admin_name = "N/A";
            }
            $row['admin']= $admin_name;
            $urls[]=$row;
            $smarty->assign ("urls", $urls);
               // echo $admin_name ;
            $smarty->assign ("admin_name ", $admin_name );
        }
        $smarty->assign ("pagination", $output);
        $smarty->assign ("page_num", $page);
        break;
    default:
        $tbl_name = "users";//your table name
        // How many adjacent pages should be shown on each side?
        $adjacents = 3;
        /*
        First get total number of rows in data table.
        If you have a WHERE clause in your query, make sure you mirror it here.
        */
        $query = "SELECT COUNT(*) as num FROM $tbl_name";
        $total_pages = $db->rawQuery($query);
        $total_pages = $total_pages[0]['num'];
        /* Setup vars for query. */
        $targetpage = "viewadmins.php";     //your file name  (the name of this file)
        $limit = 5; //how many items to show per page
        $page = $_GET['page'];
        if($page) {
            $start = ($page - 1) * $limit;     //first item to display on this page            
        } else {
            $start = 0;//if no page var is given, set start to 0
        }
        /* Get data. */
        $sql = "SELECT * FROM $tbl_name where id != 1 order by id desc LIMIT $start, $limit";
        $result = $db->rawQuery($sql);
        
        /* Setup page vars for display. */
        if ($page == 0) {
            $page = 1;    //if no page var is given, default to 1.   
        }
        $prev = $page - 1;    //previous page is page - 1
        $next = $page + 1;    //next page is page + 1
        $total_pages = ($total_pages - 1);    // decreade the total by one to fix a bug abdul 17/11/2014
        $lastpage = ceil($total_pages/$limit);//lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;//last page minus 1
        /*
        Now we apply our rules and draw the pagination object.
        We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $pagination = array();
        if ($lastpage > 1) {
        //previous button
            if ($page > 1) {
                $pagination[] = '<li><a href="'.$targetpage.'?page='.$prev.'">&laquo; previous</a></li>';
            } else {
                $pagination[] = '<li class="disabled"><a href="#"> &laquo; previous</a></li>';
            }
            $pagination[]="&nbsp;&nbsp;";
            //pages
            if ($lastpage < 7 + ($adjacents * 2)) {//not enough pages to bother breaking it up

                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination[] = "<li><a href=\"#\">$counter</a></li>";    
                    } else {
                        $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";   
                    }
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                            if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                            } else {
                            $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";
                            }
                        }
                    $pagination[] = "<li><a href=\"$targetpage?page=$lpm1\">$lpm1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination[] = "<li><a href=\"$targetpage?page=1\">1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=2\">2</a><li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"$counter\">$counter</a></li>";  
                        } else {
                            $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";  
                        }
                    }
                    $pagination[] = "<li><a href=\"$targetpage?page=$lpm1\">$lpm1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";
                } else { //close to end; only hide early pages
                    $pagination[] = "<li><a href=\"$targetpage?page=1\">1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=2\">2</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"$counter\">$counter</a></li>";  
                        } else {
                            $pagination[] = "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";   
                        }
                    }
                }
            }
            //next button
            if ($page < $lastpage ) {
                $pagination[] = "<li><a href=\"$targetpage?page=$next\">Next &raquo;  </a></li>";
            } else {
                $pagination[] = '<li class="disabled"><a href="#">Next &raquo; </a></li>';
            }
            $output  = '<ul class="pagination">';;
            foreach ($pagination as $pagina) {
                $output .= $pagina;
            }
            $output .= "</ul>";
        }
        $admins =	 array();
        #########################
        # created by Abdul Ibrahim
        # intiate $perm_available array to contain all permissions
        # with a non existing admin id = 0
        # to show the permission in the table if no admin has it
        ########################
         $permissionData = fetchAllPermissions();
         unset($permissionData[0]);
        foreach ($permissionData as $v1) {
            
            $perm_available[] = array (0 => $v1['name']);
        }
        ########### End intiate $perm_available array ##############
        foreach ($result as $row)
        {
        $admins[]=$row;
        $smarty->assign ("viewadmin", $admins);
        }

        
        if (!empty($output)){
        $smarty->assign ("pagination", $output);   
        }
        $smarty->assign ("page_num", $page);
}  
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'viewadmins';


require 'display.php';