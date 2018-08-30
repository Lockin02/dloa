$(function() {

	//项目经理
	$("#projectManager").yxselect_user({
		hiddenId : 'projectManagerId',
		mode : 'check'
	});

	// 验证信息
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

	//其他原因
	$("#checkOther").change(function () {
		if ($(this).attr("checked")) {
			$("#comOther").show().addClass('validate[required]');
		} else {
			$("#comOther").hide().removeClass('validate[required]');
		}
	});
});

//时间差验证
function timeCheck() {
	var s = plusDateInfo('thisDate', 'requireDate');
	if (s < 0) {
		alert("期望离职日期不能早于当前日期！");
		$("#requireDate").val("");
		return false;
	}
}

/** 邮箱验证 * */
function checkmail(obj) {
	mail=$(obj).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(mail))
		return true;
	else {
		alert('请输入正确的邮箱!');
		$(obj).val("");
		return false;
	}
}