$(document).ready(function() {
	$("#employName").yxselect_user({
		hiddenId : 'employId',
		mode : 'check',
		isShowLeft : true //��ʾ��ְ��Ա
	});
});

function checkData() {
	var employNameArr = ($("#employName").val()).split(',');
	var userNameArr = ($("#userName").val()).split(',');
	var isRepeat = false;
	$.each(employNameArr ,function(i ,val) {
		$.each(userNameArr ,function(j ,v) {
			if (val == v) {
				isRepeat = true;
				return false;
			}
		});
		if (isRepeat) {
			return false;
		}
	});
	if (isRepeat) {
		alert('¼�����������ظ����֣�');
		return false;
	}

	var employId = $("#employId").val();
	var employIdArr = employId.split(',');
	var employNum = 0;
	if (employId != '') {
		employNum = employIdArr.length;
	}
	if ($("#ingtryNum").val() < employNum - $("#employNum").val()) {
		alert('¼���������ܳ�������������');
		return false;
	} else if ($("#ingtryNum").val() == employNum - $("#employNum").val()){
		document.getElementById('form1').action = "?model=hr_recruitment_apply&action=editEmploy&isFinish=true";
	}
	var entryNum = $("#entryNum").val() - $("#employNum").val() + employNum;
	$("#entryNum").val(entryNum); //��������ְ����
}