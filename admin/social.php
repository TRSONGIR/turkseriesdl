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
$do = $_GET['do'];
switch($do){
    case "del":
        if(empty($_POST['check'])){
            $page_num=$POST['page_num'];
            header("location: social.php?page=$page_num");
            die();
        } elseif ($POST["enable"]="enable"){
            foreach($_POST['check'] as $v) {
                $enable = "UPDATE `social` SET `active` = '1' WHERE id='$v'";
                $enable = SanitizeString($db->escape($enable));
                $row    = $db->rawQuery($enable);
                $result  = $row[0];               
                $smarty->assign ("action", "delsocial");
            }
        } elseif ($POST["disable"]= "disable") {
            foreach($_POST['check'] as $v){
                $disable = "UPDATE `social` SET `active` = '2' WHERE id='$v'";
                $disable = SanitizeString($db->escape($disable));
                $row    = $db->rawQuery($disable);
                $result  = $row[0];
                $smarty->assign ("action", "delsocial");
            } 
        } else {
            echo 'something went wrong';
        }
        break;
    case "update":
        $smarty->assign ("action", "updatesocial");
        $id = $_GET['id'];
        if (!empty($_GET['form']) && $_GET['form'] == 1 && isset($_POST['name']) && !empty($_POST['code']) && !empty($_POST['active'])) {
            $name       = $_POST['name'];
            $code       = $_POST['code'];
            $active     = $_POST['active'];
            $id         = SanitizeString($db->escape($id));
            $name       = SanitizeString($db->escape($name));
            $code       = SanitizeString($db->escape($code));
            $active     = SanitizeString($db->escape($active));
            if ($is_demo !== "1") {
                $query      = "UPDATE `social` SET `name` = '$name', `code` = '$code', `active` = '$active' WHERE `id` = '$id'";
                $row        = $db->rawQuery($query);
            }
            $smarty->assign ("done", 1);
        }else{
            $smarty->assign ("done", 0);
            $query  ="SELECT * FROM `social` where `id` = '$id' ";
            $row    = $db->rawQuery($query);   
            

            $fetch  = $row[0];
            $smarty->assign ("socialid", $id);
            $smarty->assign ("name",    $fetch['name']);
            $smarty->assign ("code",    $fetch['code']);
            $smarty->assign ("active",  $fetch['active']);
        }
        break;
    default:
        $tbl_name="social";        //your table name
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
        $targetpage = "social.php";     //your file name  (the name of this file)
        $limit = 5;                                 //how many items to show per page
        $page = $_GET['page'];
        if($page) {
            $start = ($page - 1) * $limit;             //first item to display on this page    
        } else {
            $start = 0;                                //if no page var is given, set start to 0
        }
        /* Get data. */
        $sql    = "SELECT * FROM $tbl_name order by id desc LIMIT $start, $limit";
        $result = $db->rawQuery($sql);        /* Setup page vars for display. */
        if ($page == 0){
            $page = 1;                    //if no page var is given, default to 1.
        }
        $prev = $page - 1;                            //previous page is page - 1
        $next = $page + 1;                            //next page is page + 1
        $lastpage = ceil($total_pages/$limit);        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                        //last page minus 1
        /*
        Now we apply our rules and draw the pagination object.
        We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $pagination = array();
        if ($lastpage > 1) {
        //previous button
            if ($page > 1) {
                $pagination[] = '<li><a href="'.$targetpage.'&page='.$prev.'">&laquo; previous</a></li>';
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
                        $pagination[] = "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";   
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
                            $pagination[] = "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                            }
                        }
                        $pagination[] = "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
                        $pagination[] = "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination[] = "<li><a href=\"$targetpage&page=1\">1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage&page=2\">2</a><li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"$counter\">$counter</a></li>";  
                        } else {
                            $pagination[] = "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";  
                        }

                    }
                    $pagination[] = "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";
                } else { //close to end; only hide early pages
                    $pagination[] = "<li><a href=\"$targetpage&page=1\">1</a></li>";
                    $pagination[] = "<li><a href=\"$targetpage&page=2\">2</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination[] = "<li class=\"active\"><a href=\"$counter\">$counter</a></li>";  
                        } else {
                            $pagination[] = "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";   
                        }
                    }
                }
            }
            //next button
            if ($page < $lastpage ) {
                $pagination[] = "<li><a href=\"$targetpage&page=$next\">Next &raquo;  </a></li>";
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
        $admin_names = array();
        foreach($result as $row) {
            $urls[]=$row;
            $smarty->assign ("urls", $urls);
        }
        if (!empty($output)) {
            $smarty->assign ("pagination", $output);
        }
    $smarty->assign ("page_num", $page);
}  
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'social';
require 'display.php';
