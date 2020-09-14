<?php
if (isset($smartyvalues)) {
    foreach ($smartyvalues as $key => $value) {
        $smarty->assign ($key, $value);
    }
}
$homepage = $_SERVER['REQUEST_URI'];
$header_file = $smarty->fetch ("$template/header.html");
if (strpos($homepage, 'index.php')) {
    $footer_file = $smarty->fetch ("$template/footer_home.html");
} else {
    $footer_file = $smarty->fetch ("$template/footer.html");
}

$body_file   = $smarty->fetch ("$template/$templatefile.html");
$template_output = $header_file . $body_file   . $footer_file;
echo $template_output;
