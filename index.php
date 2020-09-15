<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width"> 
  <title>Turk Series | سریال ترکی</title>
  <style>
  @import url("https://fonts.googleapis.com/css?family=Lato:400,700|Montserrat:900");
html {
  display: grid;
  width : 100%
}
body {
   display: grid;
  background: #183059;
}



.container {
  position: relative;
  margin: auto;
  overflow: hidden;
  width: auto;
  height: auto;
}

h1 {
  font-family: "Lato", sans-serif;
  text-align: center;
  margin-top: 1em;
  font-size: 1em;
  text-transform: uppercase;
  letter-spacing: 5px;
  color: #F6F4F3;
}

#timer {
  color: #F6F4F3;
  text-align: center;
  text-transform: uppercase;
  font-family: "Lato", sans-serif;
  font-size: .7em;
  letter-spacing: 5px;
  margin-top: 1%;
}
.seconds {
  display: inline-block;
  padding: 20px;
  width: 100px;
  border-radius: 5px;
}

.seconds {
  background: #F0A202;
}

.numbers {
  font-family: "Montserrat", sans-serif;
  color: #183059;
  font-size: 4em;
}
.btn {
    font-family: Times New Roman;
    background: #2c8eea;
    color: #ffffff;
    cursor: pointer;
    font-size: 2em;
    border: 0;
    transition: all 0.5s;
    border-radius: 6px;
    width: auto;
    position: relative;
    min-width: 20px;
    height : 35px
    }


footer {
  position: fixed;
  bottom: 0;
  right: 0;
  text-transform: uppercase;
  padding: 10px;
  font-family: "Lato", sans-serif;
  font-size: 0.7em;
}
footer p {
  letter-spacing: 3px;
  color: #EF2F3C;
}
footer a {
  color: #F6F4F3;
  text-decoration: none;
}
footer a:hover {
  color: #276FBF;
}
  </style>
</head>
<body>
<!-- partial:index.partial.html -->
<div class="container">
  <h1>Creating download link...</h1>
  <div id="timer"></div>
</div>
<footer>
<a href="https://proxy.turkcyber.ml/-----https://t.me/turkseriesdl"><button class="btn">کانال تلگرام ما</button><a>
</footer>
<!-- partial -->
<script>
  let counter = 21;

const interval = setInterval(() => {
counter--;
 document.getElementById("timer").innerHTML =
    " \
<div class=\"seconds\"> \
  <div class=\"numbers\">" + counter + "</div>seconds</div> \
</div>";
	if(counter === 0){ clearInterval(interval);
}
},1000);
  </script>
  <?php
function checkRemoteFile($url)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
// don't download content
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if(curl_exec($ch)!==FALSE)
{
    return true;
}
else
{
    return false;
}
};
$file="dl1.turkseriesdl.ml/0:/".$_GET['url']."[Turkseriesdl]";
$urls = array(
"mkv" => "$file.mkv",
"mp4" => "$file.mp4",
"mov" => "$file.mov"
);
foreach($urls as $key => $value){
	if(checkRemoteFile($urls[$key]) == true){
	$url= "https://".$urls[$key];
	break;
}else{
	$url="https://proxy.turkcyber.ml/-----https://t.me/turkseriesdl/2";
}
};
header("refresh: 20;url=$url");
?>
</body>
</html>
