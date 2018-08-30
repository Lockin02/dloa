function checkform(){
	if($("#name").val() == ""){
		alert("名称不能为空");
		return false;
	}

	if($("#licenseType").val() == ""){
		alert("对应license类型不能为空");
		return false;
	}

	if($("#thisVal").val() == "" && $("#extVal").val() == ""){
		alert("没有对模板进行赋值");
		return false;
	}
	//保存后禁用保存按钮
	$("#savebtn").attr("disabled", true);
	return true;
}
 