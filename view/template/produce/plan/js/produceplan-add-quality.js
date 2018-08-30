$(document).ready(function() {
	$('#planNum').change(function () {
		if (!checkNum()) {
			$(this).val($('#effectNum').val()).focus();
		}
	});
	validate({
		"planNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"checkType" : {
			required : true
		}
	});
});

//�ύ����
function checkData() {
	if (!checkNum()) {
		return false;
	}

	return true;
}

//��������Ƿ���Ч
function checkNum() {
	if (parseInt($('#planNum').val()) > parseInt($('#effectNum').val())) {
		alert('�������ܴ������������'+$('#effectNum').val()+'��');
		return false;
	}

	if (parseInt($('#planNum').val()) <= 0) {
		alert('������������㣡');
		return false;
	}

	return true;
}