

$(document).ready(function(){

	//��ѡ��Ŀ����
	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"managerName" : {
			required : true
		}
	});
});


function checkform(){
	if($("#orgManagerId").val() == $("#managerId").val()){
		alert('ԭ��Ŀ���������Ŀ��������ͬһ��');
		return false;
	}
	return true;
}