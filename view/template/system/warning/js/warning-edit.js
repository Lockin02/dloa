$(document).ready(function() {
	//����֤
	validate({
		"objName" : {
			required : true
		},
		"executeSql" : {
			required : true
		}
	});
	
	//�Ƿ�����
	if($("#isUsingHidden").val() == 1){
		$("#isUsingY").attr('checked',true);
	}else{
		$("#isUsingN").attr('checked',true);
	}

	//�Ƿ�֪ͨ�ϼ�
	if($("#isMailManagerHidden").val() == 1){
		$("#isMailManagerY").attr('checked',true);
	}else{
		$("#isMailManagerN").attr('checked',true);
	}

	//�ı���߶�����Ӧ
	$("textarea").each(function(){
		$(this).height($(this)[0].scrollHeight);
	});
});

//���ʼ�������Id�ֶβ�Ϊ��ʱ���ʼ������������ֶ�Ҳ����Ϊ��
function checkForm(){
	if($("#receiverIdKey").val() == ''){
		$("#receiverNameKey").val('');
		return true;
	}else{
		if($("#receiverNameKey").val() == ''){
			alert('���ʼ�������Id�ֶβ�Ϊ��ʱ���ʼ������������ֶ�Ҳ����Ϊ�գ�');
			return false;
		}
	}
}