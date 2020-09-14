<?php

#########################
# protect against xss
########################
function SanitizeString($string) {

    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }    
    $string = strip_tags($string);
    return htmlentities($string);
}
function check_ref ($domain){
    if (isset($_SERVER['HTTP_REFERER'])){
        $referer = $_SERVER['HTTP_REFERER'];
        if (strpos($referer, $domain) == false) {
            die("it seems not a good request");
        } 
    }   
}
#########################
# created by Abdul Ibrahim
# 9/3/20152
# add http:// if it's not exists in the URL?
########################
 function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function replace_slashes ($str) {
        $str_clean = str_replace('\r\n', "\n", $str);
    return $str_clean;
}