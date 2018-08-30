$(document).ready(function() {
   //表单验证
	validate({
		"objName" : {
			required : true
		},
		"executeSql" : {
			required : true
		},
		"isUsing" : {
			required : true
		}
	});
});

//当邮件接收人Id字段不为空时，邮件接收人姓名字段也不能为空
function checkForm(){
	if($("#receiverIdKey").val() == ''){
		$("#receiverNameKey").val('');
		return true;
	}else{
		if($("#receiverNameKey").val() == ''){
			alert('当邮件接收人Id字段不为空时，邮件接收人姓名字段也不能为空！');
			return false;
		}
	}
}