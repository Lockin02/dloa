$(document).ready(function() {
   //����֤
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