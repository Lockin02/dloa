$(function() {

});

function sub() {
	var str = '';
	$("input[name^='leave[checkbox]']").each(function() {
		if($(this).attr("checked")) {
			str += $(this).val() + ",";
			return false; //����ѭ��
		}
	});

	if(str == "") {
		alert("��ѡ����ְԭ��");
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
			if(confirm("��Ŀ������ĿΪ�գ��Ƿ������")) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		alert("��Ա����ְ���뵥�Ѵ��ڣ�");
		return false;
	}
}

//ֱ���ύ
function toSubmit() {
	document.getElementById('form1').action = "?model=hr_leave_leave&action=staffAdd&actType=staff";
}