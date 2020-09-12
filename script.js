var timeleft, time;
timeleft = time = 30;
$("#time").html(timeleft);
$("#timer_container").fadeTo("slow",1);
$("#time").fadeTo("slow",1);
var i, j, rotation, width;  

for(i=0; i<timeleft;i++){
  document.getElementById("timer_container").innerHTML += "<div class='tictic'></div>";
} 
var x = document.getElementById("timer_container");
var y = x.getElementsByTagName("div");
width=document.getElementById("timer_container").offsetWidth;
for(i=0; i<timeleft;i++){
  rotation=(360/timeleft)*(i);
  console.log(rotation+"\n");
  console.log(width+"\n");
  y[i].style.cssText = "transform:rotate("+ rotation +"deg) translate(0px, -"+width/2+"px)";
}
var i = 0;
remainingtime = setInterval(function(){
  $("#time").html(timeleft);
  y[i].style.backgroundColor = "#ffffff";
  timeleft -= 1;
  i+=1;
  if(timeleft <= 0 && i>=time){
    clearInterval(remainingtime);
    $("div").remove(".tictic");
    $("#time").html("Time out!");
  }
}, 1000);
