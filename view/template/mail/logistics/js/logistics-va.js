function checkform(){
	if($("#companyName").val() == ""){
		alert("请输入公司名称");
		return false;
	}
	return true;
}

$(function(){
	if($("#id").length > 0){
		setSelect('isDefault');
	}
});