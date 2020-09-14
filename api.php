<script type="text/javascript">
var parts = location.href.split('#');
if(parts.length > 1)
{
    var params = parts[0].split('?');
    var mark = '?';
    if(params.length > 1)
    {
        mark = '&';
    }
    location.href = parts[0] + 'repaced_hash_fragment' + parts[1];
}
//https://mega.nz/#!bizhzdjk!s858ngxcijy0pncczblx9m4tthhwexfmfxrov1fkyzq
</script>

<?php
require 'includes/config.php';
require 'smarty.php';
if(isset($_GET['url'])){
    if(!filter_var($_GET['url'], FILTER_VALIDATE_URL)) {
    echo "it's not valid URL";
    die();
    }
    $author = 'api_author';
    $name   = 'api_name';
    $url    = $_GET['url'];
    $url    = trim($url);
    $size   = '';
    $date   = date("Y-m-d");
    $table  = 'links';  
    // if the url has a fragment
    if(stristr($url, "repaced_hash_fragment"))
    {
      $url = str_replace('repaced_hash_fragment', '#', $url);  
    }

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
} else {
    echo 'Please enter a valid URL';;
    die();
}
$templatefile = 'api';
$body_file   = $smarty->fetch ("$template/$templatefile.html");
echo $body_file ;
