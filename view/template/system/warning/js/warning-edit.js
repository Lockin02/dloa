$(document).ready(function() {
	//表单验证
	validate({
		"objName" : {
			required : true
		},
		"executeSql" : {
			required : true
		}
	});
	
	//是否启用
	if($("#isUsingHidden").val() == 1){
		$("#isUsingY").attr('checked',true);
	}else{
		$("#isUsingN").attr('checked',true);
	}

	//是否通知上级
	if($("#isMailManagerHidden").val() == 1){
		$("#isMailManagerY").attr('checked',true);
	}else{
		$("#isMailManagerN").attr('checked',true);
	}

	//文本域高度自适应
	$("textarea").each(function(){
		$(this).height($(this)[0].scrollHeight);
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