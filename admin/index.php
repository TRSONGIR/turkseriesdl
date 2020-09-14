<?php
require 'global.php';
require '../includes/config.php';
require 'smarty.php';
$id     = $_SESSION["userCakeUser"]->user_id;
if (isset($_POST['notes']) && $_POST['notes'] == 1 ) {
    $notes	= $_POST['note'];
    $query      = "UPDATE `users` SET `notes`= '$notes'  WHERE `id`='$id' ";
    //Sanitize the query
    $query      = SanitizeString(($query));
    if ($is_demo !== "1") {
        $db->rawQuery($query);
    }
    
    $smarty->assign ("done", 1);
} else {
    $smarty->assign ("done", 0);
    $query  = "SELECT `notes` FROM `users` WHERE `id` = '$id' ";
    $rows   = $db->rawQuery($query);
    $fetch  = $rows[0];
    $notes  = trim($fetch['notes']);
    $smarty->assign ("notes", $notes);
}
$query  = "SELECT COUNT(*) as num FROM `links`";
$rows   = $db->rawQuery($query);
$fetch  = $rows[0];
$alllinks   = $fetch['num'];
$smarty->assign ("alllinks", $alllinks);
$query      = "SELECT COUNT(*) as num FROM `users`";
$rows   = $db->rawQuery($query);
$fetch  = $rows[0];
$alladmins  = $fetch['num'];
$smarty->assign ("alladmins", $alladmins);
$rows   = $db->rawQuery($query);
$fetch  = $rows[0];
$allads     = $fetch['num'];
$smarty->assign ("allads", $allads);
$query  = $query  = "SELECT `vistis` FROM `config`";
$rows   = $db->rawQuery($query);
$vistis = $rows[0]['vistis'];
$smarty->assign ("vistis", $vistis);
$online = $db->get('online'); //contains an Array of all online
$count_online = $db->count;
$smarty->assign ("online", $count_online);
/// all downloads
$query      = "Select SUM(downloads) FROM links";
$rows   = $db->rawQuery($query);
$all_downloads = $rows[0]['SUM(downloads)'];
$smarty->assign ("downloads", $all_downloads);
// for downloads
$query  = "SELECT DATE(date),COUNT(id)
            FROM links
            WHERE date BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
            GROUP BY DATE(date)";

$rows   = $db->rawQuery($query);
$results  = $rows;
// for the last 7 days number of links added
$date1  = (isset($results[0]['DATE(date)'])) ? $results[0]['DATE(date)'] : date('Y-m-d'); // today
$count1 = (isset($results[0]['COUNT(id)']))  ? $results[0]['COUNT(id)']  : 0; 
$date2  = (isset($results[1]['DATE(date)'])) ? $results[1]['DATE(date)'] : date('Y-m-d',strtotime( "-1 days")); // minus 1 day;
$count2 = (isset($results[1]['COUNT(id)']))  ? $results[1]['COUNT(id)']  : 0; 
$date3  =  (isset($results[2]['DATE(date)']))? $results[2]['DATE(date)'] : date('Y-m-d',strtotime( "-2 days")); // minus 2 day;
$count3 = (isset($results[2]['COUNT(id)']))  ? $results[2]['COUNT(id)']  : 0; 
$date4  = (isset($results[3]['DATE(date)'])) ? $results[3]['DATE(date)'] : date('Y-m-d',strtotime( "-3 days")); // minus 3 day;
$count4 = (isset($results[3]['COUNT(id)']))  ? $results[3]['COUNT(id)']  : 0; 
$date5  = (isset($results[4]['DATE(date)'])) ? $results[4]['DATE(date)'] : date('Y-m-d',strtotime( "-4 days")); // minus 4 day;
$count5 = (isset($results[4]['COUNT(id)']))  ? $results[4]['COUNT(id)']  : 0;
$date6  = (isset($results[5]['DATE(date)'])) ? $results[5]['DATE(date)'] : date('Y-m-d',strtotime( "-5 days")); // minus 5 day;
$count6 = (isset($results[5]['COUNT(id)']))  ? $results[5]['COUNT(id)']  : 0;
$date7  = (isset($results[6]['DATE(date)'])) ? $results[6]['DATE(date)'] : date('Y-m-d',strtotime( "-6 days")); // minus 6 day;
$count7 = (isset($results[6]['COUNT(id)']))  ? $results[6]['COUNT(id)']  : 0;

$smarty->assign ("date1",   $date1);
$smarty->assign ("count1",  $count1);
$smarty->assign ("date2",   $date2);
$smarty->assign ("count2",  $count2);
$smarty->assign ("date3",   $date3);
$smarty->assign ("count3",  $count3);
$smarty->assign ("date4",   $date4);
$smarty->assign ("count4",  $count4);
$smarty->assign ("date5",   $date5);
$smarty->assign ("count5",  $count5);
$smarty->assign ("date6",   $date6);
$smarty->assign ("count6",  $count6);
$smarty->assign ("date7",   $date7);
$smarty->assign ("count7",  $count7);
///////// count links that never been downloaded
$query = "Select SUM(downloads=0) FROM links";
$rows  = $db->rawQuery($query);
$never_downloads = $rows[0]['SUM(downloads=0)'];

// links downloaded
$query = "Select SUM(downloads>0) FROM links";
$rows  = $db->rawQuery($query);
$has_downloaded = $rows[0]['SUM(downloads>0)'];

$smarty->assign ("never_downloads", $never_downloads);
/// count links that has been downloaded
$smarty->assign ("has_downloaded", $has_downloaded);
$smarty->assign ("is_demo", $is_demo);
$templatefile = 'index';
require 'display.php';
