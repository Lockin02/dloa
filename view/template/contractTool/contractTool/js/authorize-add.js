$(function(){
	$("#userName").yxselect_user({
		hiddenId : 'userCode',
		mode : "check",
		event : {
			"select" : function(obj, row) {
				authorizeList();
			}
		}
	});
	validate({
		"userName" : {
			required : true
		}
	})
});

/**
 * ��̬���ú�ͬȨ�������б�
 */
function authorizeList() {
	var userCode = $("#userCode").val();
	var userName = $("#userName").val();
	$.ajax({
		type : 'POST',
		url : '?model=contractTool_contractTool_authorize&action=checkUser',
		data : {
			userCode : userCode,
			userName : userName
		},
		async : false,
		success : function(data) {
			if(data == "0"){
				alert("���û��Ѿ�����Ȩ��");
			}
		}
	});
}



function check(){
	if($('#userName').val()==''){
		alert('��ָ���û�����');
		return false;
	}
}

//�ж��Ƿ�Ȩ
function sub(){
	$("form").bind("submit", function() {
		var cl = $("input:checkbox:checked").length;
		if(cl == 0){
			alert("��ѡ����Ҫ�����Ȩ��");
			return false;
		}
	});
}