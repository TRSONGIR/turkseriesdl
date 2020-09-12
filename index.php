<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Turk Series | سریال ترکی</title>
  <link rel="stylesheet" href="./style.css">
  <script type="text/javascript">
    (function(){
    var now = new Date();
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.async = true;
    var script_address = 'https://cdn.yektanet.com/js/TURKSERIESDL.ML/native-TURKSERIESDL.ML-12695.js';
    script.src = script_address + '?v=' + now.getFullYear().toString() + '0' + now.getMonth() + '0' + now.getDate() + '0' + now.getHours();
    head.appendChild(script);
    })();
   </script>
</head>
<body>
<div id="pos-article-display-12438"></div>
<div id="pos-notification-12441"></div>
<p>دانلود پس از 10 ثانیه به طور خوردکار شروع می شود</p>
<!-- partial:index.partial.html -->
<div id="timer_container">
   <p id="time"></p>
 </div>
<!-- partial -->
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script><script  src="./script.js"></script>
<?php
$dlno = "http://dl1.turkseriesdl.ml/0:/";
$shortener = $_GET ['url'];
$url = $dlno.$shortener;
header("Refresh: 10;url=$url"); 
?>
<div id="pos-footer-sticky-12442"></div>
</body>
</html>
