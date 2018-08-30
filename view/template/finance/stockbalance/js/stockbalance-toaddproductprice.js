$(function(){
	$("#thisMonth").val($("#thisMonthHide").val());
});
//表单验证方法
function checkform(){
	if($("#thisYear").val() =="" ){
		alert("年份需要填写");
		return false;
	}

	if($("#inputExcel").val() =="" ){
		alert("请选择需要导入的EXCEL文件");
		return false;
	}

//	alert("当前功能未完成");
	$("#loading").show();

	return true;
}