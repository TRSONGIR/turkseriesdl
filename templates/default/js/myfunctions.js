

function NewWindow(mypage,myname,w,h,scroll){
var winl = (screen.width-w)/2;
var wint = (screen.height-h)/2;
var settings ='height='+h+',';
settings +='width='+w+',';
settings +='top='+wint+',';
settings +='left='+winl+',';
settings +='scrollbars='+scroll+',';
settings +='resizable=yes';
win=window.open(mypage,myname,settings);
if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
function check_it() {
     var theurl=document.formurl.url.value;
     var tomatch= /https?:\/\/w{0,3}\w*?\.(\w*?\.)?\w{2,3}\S*|www\.(\w*?\.)?\w*?\.\w{2,3}\S*|(\w*?\.)?\w*?\.\w{2,3}[\/\?]\S*/
     if (tomatch.test(theurl))
     {
         
         return true;
     }
     else
     {
         window.alert("The link is invalid. Try again.");
         return false; 
     }
}
(function(){
   var message = "%d seconds before download link appears";
   // seconds before download link becomes visible
   var count = seconds; // how many seconds to wait
   var countdown_element = document.getElementById("countdown");
   var download_link = document.getElementById("download_link");
   var timer = setInterval(function(){
      // if countdown equals 0, the next condition will evaluate to false and the else-construct will be executed
      if (count) {
          // display text
          countdown_element.innerHTML = "You have to wait %d seconds.".replace("%d", count);
          // decrease counter
          count--;
      } else {
          // stop timer
          clearInterval(timer);
          // hide countdown
          countdown_element.style.display = "none";
          // show download link
          download_link.style.display = "";
      }
   }, 1000);
})();