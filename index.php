<?php
$a= "header("Refresh: 10;url=http://www.20script.ir/");";
$b= $_GET['url'];
$c= $a.$b;
header("Refresh: 17;url=$c");
?>
<BODY>
<style>
#minutes, #seconds{
display: inline-block;
background-color: #FF1493;
border-radius: 10px;
color: white;
width: auto;
  min-width:70px;
margin-left: 10px;
padding: 14px;
}
.holder
{
width: 100%;
margin: auto;
text-align: center; 
}
  #minutesTxt, #secondsTxt{
  font-size: 27px;}
</style>

<script>   
var given_seconds = 20; //fifteen seconds
   
setInterval(function() {
 
    // Count down by one second
    given_seconds=given_seconds-1;
     
    // Calculate hour/mins/sec's based on given seconds
    hours = Math.floor(given_seconds / 3600);
    minutes = Math.floor((given_seconds - (hours * 3600)) / 60);
    seconds = given_seconds - (hours * 3600) - (minutes * 60);
    
      // Format the time with two digits per hour/mins/sec's
   
    minutesString = minutes.toString().padStart(2, '0') + '';
    secondsString = seconds.toString().padStart(2, '0') + '';
  
  
    // Print the current time so far
    document.getElementById("minutesTxt").innerHTML = minutesString;
	  document.getElementById("secondsTxt").innerHTML = secondsString;
  
    // Check if countdown is complete & redirect
    if (given_seconds==1)
      window.top.location='https://t.me/turkseriesdl/';

}, 1000); // Update about every second
</script>
<div class="holder">
  <div id="minutes" style="display:none"><span id="minutesTxt"></span><br>Minutes</div><div id="seconds"><span id="secondsTxt"></span><br>Seconds</div>  
<br><br>
</div>
</BODY>