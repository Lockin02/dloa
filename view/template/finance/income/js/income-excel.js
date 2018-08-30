//表单验证方法
function checkform(){
	if($("#inputExcel").val() =="" ){
		alert("请选择需要导入的EXCEL文件");
		return false;
	}

//	alert("当前功能未完成");
	$("#loading").show();

	return true;
}

function changeIsCheck(thisVal){
	$("#isCheck").val(thisVal);
}