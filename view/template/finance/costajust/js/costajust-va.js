function checkform(){
	if($("#stockName").val() == ""){
		alert('请选择仓库名称');
		return false;
	}
	if($("#stockId").val() == ""){
		alert('没有正确选择仓库,请重新选择');
		return false;
	}
	if($("#formDate").val() == ""){
		alert('请选择日期');
		return false;
	}
	return true;
}