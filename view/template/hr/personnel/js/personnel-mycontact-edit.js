$(document).ready(function() {
	validate({
		"mobile" : {
			required : true
		},
		"personEmail" : {
			required : true
		},
		"emergencyTel" : {
			required : true
		},
		"emergencyName" : {
			required : true
		},
		"city2" : {
			required : true
		},
		"homeAddress" : {
			required : true
		}
	});

	$("#mobile").blur(function() {
		$("#mobilePhone").val($(this).val());
	});
});

/** 邮箱验证 * */
function checkmail(obj) {
	mail = $(obj).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(mail)) {
		return true;
	} else {
		alert('请输入正确的邮箱!');
		$(obj).val("");
		return false;
	}
}