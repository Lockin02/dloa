function createXMLHttpRequest() {
     var xmlhttp;
     try {
   xmlhttp = new XMLHttpRequest();       
     } catch(e) {
         try {
             xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
         } catch(e) {
             try {
          xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');         
             } catch(e) {
                 alert("创建XMLHttpRequest对象失败?");
             }
         }
     }
     return xmlhttp;
} 
var lis;
var index=0;
var status=0;
var objid="";
var setwidth=0;
function change(obj,tab,sel,dir){
    var value=obj.value;
    if(value == ""){
        document.getElementById('sug').style.display = 'none';
        return false;
       }
       document.getElementById('sug').style.display = 'block';
       var xmlhttp = createXMLHttpRequest();
        var url = dir+'sea.php?seaKey='+value+"&seaTable="+tab+"&seaSel="+sel;   
        xmlhttp.open('GET',url,false); 
        xmlhttp.onreadystatechange = function(){
         if(xmlhttp.readyState == 4){
          if(xmlhttp.status == 200){
		  //var index=xmlhttp.responseText.indexOf(value)+1;
		 // var v;
		 //if(index>=0){
			//v="<font color='red'>"+xmlhttp.responseText.substring(0,index)+"</font>"+xmlhttp.responseText.substring(index);
		// }
		  //var v="<div id='show'>"+xmlhttp.responseText+"</div>";
           document.getElementById('sug').innerHTML=xmlhttp.responseText;
           document.getElementById('sug').style.width=setwidth; 
		   //alert(document.getElementById('sug').innerHTML);
			
           lis = document.getElementsByTagName('li');
           index=0;
           status=0;      
          }
         }
        }   
        xmlhttp.setRequestHeader("cache-control","no-cache");
        xmlhttp.send(null); 
}
var time;
function triem(value){
   document.getElementById(objid).value = value;  
   document.getElementById('sug').style.display = 'none'; 
   clearTimeout(time);
}
function losefouse(){
   time=setInterval("triem('')",300); 
}

function keydown(){
   if(lis && lis.length >0){
    if(event.keyCode == 38){
     //向上;  
     if(index >0){
      lis[index-1].style.background='#d7ebff';     
     }
     lis[index].style.background='#ffffff';
     if(index > 0)
      index--;       
    }
    if(event.keyCode == 40){
     //向下;       
     if(index < lis.length-1)
      index++;    
     if(status == 0){
      index=0;
     }
     lis[index].style.background='#d7ebff';
     if(index >0){     
      lis[index-1].style.background='#ffffff';
     }
     status=1;   
    } 
   } 
}
function onkeydown(){
   if(lis && lis.length >0){ 
    if(index>=0){
     if(event.keyCode == 13){
        var tv=lis[index].innerHTML;
		var regEx = /<[^>]*>/g; 
		tv=tv.replace(regEx, ""); 
        triem(tv);      
     }
    }
   }  
}
function setdiv(){
    var ox=window.event.offsetX;
    var oy=window.event.offsetY;
    var x=window.event.clientX; 
    var y=window.event.clientY; 
    var oSource = window.event.srcElement ;
    objid=oSource.id;
    setwidth=oSource.style.width;
    document.getElementById('sug').style.posTop=y-oy+16;
    document.getElementById('sug').style.posLeft=x-ox;   
}