$(document).ready(function(){
	$("#createName").yxselect_user({
		formCode : 'audit'
	});
})

function CheckForm(){
	if($("#beginDate").val() == ""){
		alert("开始时间不能为空");
		return false;
	}
	if($("#endDate").val() == ""){
		alert("结束时间不能为空");
		return false;
	}
	if($("#createName").val() == ""){
		alert("填写人不能为空");
		return false;
	}
	if(confirm('确定取消审核吗?')){
		return true;
	}
	else{
		return false;
	}
}
