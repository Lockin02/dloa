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

//提交检验
function checkData() {
	if (!checkNum()) {
		return false;
	}

	return true;
}

//检查数量是否有效
function checkNum() {
	if (parseInt($('#planNum').val()) > parseInt($('#effectNum').val())) {
		alert('数量不能大于最大数量【'+$('#effectNum').val()+'】');
		return false;
	}

	if (parseInt($('#planNum').val()) <= 0) {
		alert('数量必须大于零！');
		return false;
	}

	return true;
}