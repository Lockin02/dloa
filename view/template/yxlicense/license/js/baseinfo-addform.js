

//��ʾ/���ض���
function dis(name){
	var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if(a.length>0){
      $("#div"+name).remove();
    }else{
      $("#"+name).append("<div id=div" + name + ">��</div>");
    }




}

var thisFocus = "";
//��ʾ����ĳ���� - ����flee
function disAndfocus(name) {
	if( document.activeElement.id == ""){
		var temp = document.getElementById(name);
		temp.value = $("#" + name + "_v").html();
		if (temp.style.display == '')
			temp.style.display = "none";
		else if (temp.style.display == "none")
			temp.style.display = '';
			temp.focus();
	}
}

//input��ֵ
function changeInput(focusId){
	tempVal = $("#"+ focusId).val();
	$("#"+ focusId).val(tempVal);
	$("#"+ focusId).hide();
	$("#"+ focusId + "_v").html(tempVal);
}