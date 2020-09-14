
function popup(name, url){
  toolbar_str = 'no';
  menubar_str = 'no';
  statusbar_str = 'no';
  scrollbar_str = 'yes';
  resizable_str ='yes';
  window.open(url, name, 'width=500,height=400,toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
}

function viewblock(bindex){
	for(i=1;i<=3;i++){
		obj=document.getElementById("block"+i);
			if(obj.style.visibility!="visible" && i==bindex){
				obj.style.display="block";
				obj.style.visibility="visible";
			}else{
				obj.style.display="none";
				obj.style.visibility="hidden";
			}
	}
	return true;
}
// -->
function checkAll(form){
  for (var i = 0; i < form.elements.length; i++){    
    eval("form.elements[" + i + "].checked = form.elements[0].checked");  
  } 
}


function alert4d(form,msg){
  
  var x=0;
  for (var i = 0; i < form.elements.length; i++){    
        if(form.elements[i].checked == true)x++;
  } 
  
  if(x == 0)
    alert(msg);
}

var win= null;
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

function MM_openBrWindow(theURL,winName,features) {
window.open(theURL,winName,features);
}
function check_it() {
     var theurl=document.formurl.url.value;
     var tomatch= /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/
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
