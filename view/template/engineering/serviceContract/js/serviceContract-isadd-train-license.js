/********************************培训计划控制段***********************/
function checkTrainTA(obj){
    var contentdiv=document.getElementById("tra");
    var contentdiv2 = document.getElementById("mytra");
		if(obj.value=="1"){
		 	contentdiv.style.display="";
		 	contentdiv2.style.display="";
		}else{
			 contentdiv.style.display="none";
			 contentdiv2.style.display="none";
		}
}
function OpenUserWindow(){
	URL="module/user_select";
	loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
	loc_y=document.body.scrollTop+event.clientY-event.offsetY+140;
	window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:265px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
}

/********************************培训计划控制段***********************/