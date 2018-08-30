/********************************邮件控制段***********************/
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
    var contentdiv=document.getElementById("maildiv2");
		if(obj.value=="1"){
		 	addressdiv.style.display="";
		}else{
			 addressdiv.style.display="none";
		}
}
function OpenUserWindow(){
	URL="module/user_select";
	loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
	loc_y=document.body.scrollTop+event.clientY-event.offsetY+140;
	window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:265px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
}
function clearTO(){
	$("#TO_ID").val("");
	$("#TO_NAME").val("");
}

/********************************邮件控制段***********************/