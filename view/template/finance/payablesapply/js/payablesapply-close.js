$(function() {
	//邮件通知人设置
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID'
	});
});


//表单验证
function checkform(){
	if(strTrim($("#closeReason").val()) == ""){
		alert('关闭原因不能为空！');
		return false;
	}
	if(confirm("确定要关闭付款申请吗?")){
		return true;
	}else{
		return false;
	}
}