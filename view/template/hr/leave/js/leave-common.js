$(function() {

	//��Ŀ����
	$("#projectManager").yxselect_user({
		hiddenId : 'projectManagerId',
		mode : 'check'
	});

	// ��֤��Ϣ
	validate({
		"requireDate" : {
			required : true
		},
		"mobile" : {
			required : true
		},
		"personEmail" : {
			required : true
		},
		"postAddress" : {
			required : true
		}
	});

	//����ԭ��
	$("#checkOther").change(function () {
		if ($(this).attr("checked")) {
			$("#comOther").show().addClass('validate[required]');
		} else {
			$("#comOther").hide().removeClass('validate[required]');
		}
	});
});

//ʱ�����֤
function timeCheck() {
	var s = plusDateInfo('thisDate', 'requireDate');
	if (s < 0) {
		alert("������ְ���ڲ������ڵ�ǰ���ڣ�");
		$("#requireDate").val("");
		return false;
	}
}

/** ������֤ * */
function checkmail(obj) {
	mail=$(obj).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(mail))
		return true;
	else {
		alert('��������ȷ������!');
		$(obj).val("");
		return false;
	}
}