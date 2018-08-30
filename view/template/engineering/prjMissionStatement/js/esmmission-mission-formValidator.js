$(document).ready(function() {
	$("#officeName").yxcombogrid_office();

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
		}
	});
	$("#name").formValidator({
		onshow : "请输入项目名称",
		onfocus : "项目名称至少2个字符,最多50个字符",
		oncorrect : "您输入的项目名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "项目名称两边不能有空符号"
		},
		onerror : "你输入的项目名称,请确认"
	});

	$("#officeName").formValidator({
		onshow : "请选择办事处",
		onfocus : "请选择",
		oncorrect : "您选择的办事处有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择办事处"
	});
})


/********************************邮件控制段***********************/
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
    var contentdiv=document.getElementById("maildiv2");
		if(obj.value=="1"){
		 	addressdiv.style.display="";
		 	contentdiv.style.display="";
		}else{
			 addressdiv.style.display="none";
			 contentdiv.style.display="none";
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