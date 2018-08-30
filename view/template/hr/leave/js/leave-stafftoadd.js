$(function() {

});

function sub() {
	var str = '';
	$("input[name^='leave[checkbox]']").each(function() {
		if($(this).attr("checked")) {
			str += $(this).val() + ",";
			return false; //跳出循环
		}
	});

	if(str == "") {
		alert("请选择离职原因！");
		return false;
	}

	if(!$("#comOther").hasClass('validate[required]')) {
		$("#comOther").val('');
	}

	var flag =  $.ajax({
		type : 'POST',
		url : '?model=hr_leave_leave&action=getLeaveInfo',
		data:{
			userAccount : $("#userAccount").val()
		},
		async: false,
		success : function(data) {
			return data;
		}
	}).responseText;

	if(flag == '0') {
		if(!$("#projectManager").val()) {
			if(confirm("项目经理栏目为空，是否继续？")) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		alert("该员工离职申请单已存在！");
		return false;
	}
}

//直接提交
function toSubmit() {
	document.getElementById('form1').action = "?model=hr_leave_leave&action=staffAdd&actType=staff";
}